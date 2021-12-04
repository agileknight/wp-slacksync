import React from 'react';
import RegularMessage from './RegularMessage';
import BotMessage from './BotMessage';
import MessageWithFile from './MessageWithFile'
import MessageWithAttachment from './MessageWithAttachment'
import MessageWithImageAttachment from './MessageWithImageAttachment'
import MessageWithShareAttachment from './MessageWithShareAttachment'
import DaySeparator from './DaySeparator'

function isSameDay(tsA, tsB) {
  return new Date(tsA * 1000).toDateString() == new Date(tsB * 1000).toDateString()
}

function MessagesDisplay(props) {
  return (
    <>
      {props.messageItems.map(function(item, i, arr) {
        const renderedMessage = function () {
          if (item.type == 'bot') {
            return <BotMessage key={item.ts} {...item} />
          } else if (item.type == 'with_file') {
            return <MessageWithFile key={item.ts} {...item} />
          } else if (item.type == 'with_image_attachment') {
            return <MessageWithImageAttachment key={item.ts} {...item} />
          } else if (item.type == 'with_file_attachment') {
            return <MessageWithAttachment key={item.ts} {...item} />
          } else if (item.type == 'with_share_attachment') {
            return <MessageWithShareAttachment key={item.ts} {...item} />
          }
          return <RegularMessage key={item.ts} {...item} />
        }();
        return (
          <>
            {i > 0 && !isSameDay(item.ts, arr[i-1].ts) && <DaySeparator key={item.ts + 'ds'} />}
            {renderedMessage}
          </>
        )}
      )}
    </>
  );
}

export default MessagesDisplay;
