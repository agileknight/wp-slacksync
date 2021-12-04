import React from 'react';

function ThreadInfo(props) {
  if (!props.showThreadInfo) {
    return null;
  }
  if (props.isThreadBroadcast) {
    return (
      <div className="wpslacksync-chat-content wpslacksync-thread-link" data-channel={props.channelId} data-thread={props.threadTs}>View thread</div>
    )
  }
  if (props.numberOfReplies > 0) {
    return (
      <div className="wpslacksync-chat-content wpslacksync-thread-link" data-channel={props.channelId} data-thread={props.threadTs}>{props.numberOfReplies} replies</div>
    );
  }
  if (props.showReply) {
    return (
      <div className="wpslacksync-chat-content wpslacksync-thread-link" data-channel={props.channelId} data-thread={props.threadTs}>Reply</div>
    )
  }
  return null;
}

export default ThreadInfo;
