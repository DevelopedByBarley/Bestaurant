import React from 'react';











const TimelineItem = () => (
  <li className="mb-10 ms-6">
    <span className="absolute flex items-center justify-center w-6 h-6 bg-blue-100 rounded-full -start-3 ring-8 ring-white dark:ring-gray-900 dark:bg-blue-900">
      <svg className="w-2.5 h-2.5 text-blue-800 dark:text-blue-300" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
        <path d="M20 4a2 2 0 0 0-2-2h-2V1a1 1 0 0 0-2 0v1h-3V1a1 1 0 0 0-2 0v1H6V1a1 1 0 0 0-2 0v1H2a2 2 0 0 0-2 2v2h20V4ZM0 18a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V8H0v10Zm5-8h10a1 1 0 0 1 0 2H5a1 1 0 0 1 0-2Z" />
      </svg>
    </span>
    <h3 className="flex items-center mb-1 text-lg font-semibold text-gray-900 dark:text-white">
      10:00-15:00

    </h3>
    <div className="border p-3 my-3 md:w-2/5 bg-emerald-500 text-white">
      <time className="block mb-2 text-sm font-normal leading-none dark:text-gray-500">
        <strong>
          13:15-14:15
        </strong>
        <h1 className='my-1 text-lg'>Szaniszló Árpád</h1>
      </time>
    </div>
    <div className="border p-3 my-3 md:w-2/5 bg-rose-500 text-white">
      <time className="block mb-2 text-sm font-normal leading-none">
        <strong>
          13:30-14:30 
        </strong>
        <h1 className='my-1 text-lg'>Szaniszló Árpád</h1>
      </time>
    </div>
  </li>
);



export default TimelineItem;
