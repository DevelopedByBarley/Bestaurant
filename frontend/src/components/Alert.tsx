import React, { useState } from 'react';

type AlertTypes = {
  title: string,
  content: string
}

const Alert = ({ title, content }: AlertTypes) => {
  const [show, setShow] = useState(false);

  return (
    <>
      <button onClick={() => setShow(true)} className="bg-cyan-500 text-white px-2 py-2 rounded-md cursor-pointer">
        <svg className="flex items-center justify-center w-4 h-4 text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
          <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z" />
        </svg>
      </button>
      {show && (
        <div id="alert-additional-content-1" className="fixed inset-0 flex items-center justify-center z-50" onClick={() => setShow(false)}>
          <div className="absolute inset-0 bg-black opacity-50 transition-opacity"></div>
          <div className="relative p-8 bg-white rounded-lg shadow-lg max-w-screen-lg">
            <div className="flex items-center">
              <svg className="flex-shrink-0 w-4 h-4 me-2 text-cyan-500" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z" />
              </svg>
              <h3 className="text-lg font-medium">{title}</h3>
            </div>
            <div className="mt-2 mb-4 text-sm text-left">
              {content}
            </div>
          </div>
        </div>
      )}
    </>
  );
}

export default Alert;
