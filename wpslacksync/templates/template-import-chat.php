<?php $ajax_nonce = wp_create_nonce( 'wpslacksync-slack-archive-import-ajax' ); ?>
<style type="text/css">
    #uploadSlackZip{padding:10px;height:auto;line-height:normal;}
#wpslacksyncloader{display:none;width:100%;height:100%;position:fixed;top:0;left:0;background-color:rgba(255,255,255,0.7);text-align:center;}
#wpslacksyncloader svg{vertical-align:middle;width:100%;margin:auto;top:0;bottom:0;position:inherit;left:0;right:0;}
.wpssProgressInfo{position:absolute;bottom:50px;right:50px;}
.cim0,
.cim1{display:inline-block;color:#fff;font-weight:normal;border-radius:5px;padding:5px 15px;font-size:13px;}
.cim0{background:#1164a3}
.cim1{background:#cd2553}
</style>
<?php wp_enqueue_media(); ?>
<table class="form-table">
    <tr>
        <th><label for="uploadSlackZip"><?php _e('Import zip file', WPSlackSync::$text_domain) ?></label></th>
        <td>
            <a id="uploadSlackZip" href="javascript:void(0)" class="button large"><span class="dashicons dashicons-media-archive"></span>Import zip file</a>
            <i class="fa fa-info-circle tooltip" aria-hidden="true" title="<?php _e('Upload a Slack workspace export zip file', WPSlackSync::$text_domain) ?>"></i>
        </td>
    </tr>   
    <tr><td><?php submit_button(); ?></td></tr>
</table>
<div id="wpslacksyncloader"><svg width="10%" height="10%" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100" preserveAspectRatio="xMidYMid" class="lds-reload"><g transform="rotate(341.569 50.0001 50)"><path d="M50 15A35 35 0 1 0 74.787 25.213" fill="none" ng-attr-stroke="{{config.color}}" ng-attr-stroke-width="{{config.width}}" stroke="#0073aa" stroke-width="12"></path><path ng-attr-d="{{config.darrow}}" ng-attr-fill="{{config.color}}" d="M49 3L49 27L61 15L49 3" fill="#0073aa"></path><animateTransform attributeName="transform" type="rotate" calcMode="linear" values="0 50 50;360 50 50" keyTimes="0;1" dur="1s" begin="0s" repeatCount="indefinite"></animateTransform></g><g><text x="100" y="70" font-family="Verdana" font-size="55" fill=""></text></g></svg><div class="wpssProgressInfo"><h3></h3><span></span></div></div>
<script type="text/javascript">
    var wpssync=function(){
        var filename;
        var aid;
        var import_files;
        var file_sizes;
        this.init=function(){
            $=jQuery;
            wpssync.uploadSlackZip();
        };
        this.loader=function(html){
            $('#wpslacksyncloader').show();$('#wpslacksyncloader svg g text').html(html)
        };
        this.kill_loader=function(){
          $('#wpslacksyncloader').hide();$('#wpslacksyncloader svg g text').html('Loading...');
        };
        this.uploadSlackZip=function(){
            $('#uploadSlackZip').click(function(e){
                e.preventDefault();
                wpszipup=wp.media({title:'Import Slack archive zip',button:{text:'Start import'},multiple:false,library:{type:'application/zip'}}).on('select',function(){
                    atch=wpszipup.state().get('selection').first().toJSON();
                    filename    =   atch.filename;
                    aid         =   atch.id;
                    wpssync.extract_zip();
                }).open()
            });
        };
        this.extract_zip=function(){
            $.ajax({
                type:'POST',context:this,dataType:'json',url:ajaxurl,
                data:{'action':'wpslacksync_do_ajax','act':'extract_zip','aid':aid,'filename':filename,'security':'<?php echo $ajax_nonce; ?>'},
                beforeSend:function(){wpssync.loader('Extracting zip file...')},
                success:function(data){
                    if(data){
                       if(data.status==='success'){
                           import_files = data.files;
                           file_sizes = data.file_sizes;
                           wpssync.import_messages(0);
                       }
                    }
                },
                error:function (error) {
                    wpssync.handleError(error);
                }
            });
        };
        this.import_messages=function(next_file_index){
            if (next_file_index >= import_files.length) {
                wpssync.cleanup();
                return;
            }

            var batch_size_min_bytes = 2*1024*1024;
            var batch_end_index = next_file_index-1;
            var batch_size_bytes = 0;
            while (batch_size_bytes < batch_size_min_bytes) {
                batch_end_index++;
                if (batch_end_index >= file_sizes.length) {
                    break;
                }
                batch_size_bytes += file_sizes[batch_end_index];
            }
            var next_batch = import_files.slice(next_file_index, batch_end_index+1);
            $.ajax({
                type:'POST',context:this,dataType:'json',url:ajaxurl,
                data:{'action':'wpslacksync_do_ajax','act':'import_files','import_files':next_batch.join(),'security':'<?php echo $ajax_nonce; ?>'},
                beforeSend:function(){wpssync.loader('Import running...')},
                success:function(data){
                    if(data){
                        if(data.status==='success'){
                            log="Imported <span class='cim0'>"+next_file_index+"</span> of <span class='cim1'>"+import_files.length+"</span> message files";
                            $('.wpssProgressInfo span').html(log);
                            wpssync.import_messages(next_file_index + next_batch.length);
                        }
                    }
                },
                error:function (error) {
                    wpssync.handleError(error);
                }
            });
        };
        this.exitWithMessage=function(message){
            $('.wpssProgressInfo span').html('');
            $('#wpslacksyncloader').hide();
            alert(message);
        };
        this.handleError=function(error){
            console.error('wpslacksync error: ' + JSON.stringify({status: error.status, response: error.responseJSON}));
            wpssync.exitWithMessage('An error occured, see the javascript console for details');
        };
        this.cleanup=function(){
            $.ajax({
                type:'POST',context:this,dataType:'json',url:ajaxurl,
                data:{'action':'wpslacksync_do_ajax','act':'cleanup','filename':filename,'security':'<?php echo $ajax_nonce; ?>'},
                beforeSend:function(){wpssync.loader('Cleaning up...')},
                success:function(data){
                    if(data){
                        if(data.status==='success'){
                            wpssync.exitWithMessage('All slack data imported successfully.');
                        }
                    }
                },
                error:function (error) {
                    wpssync.handleError(error);
                }
            });
        };
    };
    wpssync=new wpssync();
    wpssync.init();
</script>
