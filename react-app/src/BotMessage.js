import React from 'react';
import ThreadInfo from './ThreadInfo';

function BotMessage(props) {
  return (
    <div class="wpslacksync-p" style={{color: props.color}}>
      {props.username} <span className="wpslacksync-bot-label">BOT</span> <span className="wpslacksync-hover-toggle" data-toggle={props.timestamp}>{props.time}</span><br />
      <div className="wpslacksync-chat-content" dangerouslySetInnerHTML={{__html: props.text}} />
      <ThreadInfo {...props.thread} />
    </div>
  );
}

export default BotMessage;
