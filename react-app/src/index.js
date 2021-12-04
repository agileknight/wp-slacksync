import React from 'react';
import ReactDOM from 'react-dom';
import MessagesDisplay from  './MessagesDisplay'

export function renderMessagesDisplay(messageItems, targetElement) {
  ReactDOM.render(
    <React.StrictMode>
      <MessagesDisplay messageItems={messageItems} />
    </React.StrictMode>,
    targetElement,
  );
}
