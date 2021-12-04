import React from 'react';
import ThreadInfo from './ThreadInfo';

function RegularMessage(props) {
  return (
    <div className="wpslacksync-p wpslacksync-popup-parent" style={{color: props.message_color}}>
      <div className="wpslacksync-popup-user-profile wpslacksync-popup toggler-slack-menu" style={{display: "none"}}><img className="wpslacksync-chat-user-img img-circle" src={props.img_big} /><br />{props.realname}</div><span className="wpslacksync-popup-target wpslacksync-popup-target-style" data-popup-relative="parent" data-popup-selector=".wpslacksync-popup"><img className="wpslacksync-chat-user-img img-circle" src={props.img} /><span style={{color: props.username_color}} className="wpslacksync-username-label"><span className="wpslacksync-hover-toggle wpslacksync-popup-target-style" data-toggle={props.realname}>{props.username}</span></span></span> <span className="wpslacksync-hover-toggle" data-toggle={props.timestamp}>{props.time}</span><br />
      <div className="wpslacksync-chat-content" dangerouslySetInnerHTML={{__html: props.text}} />
      <ThreadInfo {...props.thread} />
    </div>
  );
}

export default RegularMessage;
