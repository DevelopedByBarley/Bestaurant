import React from 'react'
import { Link } from 'react-router-dom'

const Error = () => {
  return (
    <>
      <section className="bg-white dark:bg-gray-900 h-screen flex items-center justify-center">
        <div className="py-8 px-4 mx-auto max-w-screen-xl lg:py-16 lg:px-6">
          <div className="mx-auto max-w-screen-sm text-center">
            <h1 className="mb-4 text-7xl tracking-tight font-extrabold lg:text-9xl text-blue-500">404</h1>
            <p className="mb-4 text-3xl tracking-tight font-bold text-gray-900 md:text-4xl dark:text-white">Valami hiányzik <span className='text-6xl mx-1'>&#128546;</span></p>
            <p className="mb-4 text-lg font-light text-gray-500 dark:text-gray-400">Sajnáljuk, ez az oldal nem található. Kréjük lépjen a kezdőoldalra! </p>
            <div className="flex items-center justify-center mt-6 gap-x-3">
              <button className="flex items-center justify-center w-1/2 px-5 py-2 text-sm text-gray-700 transition-colors duration-200 bg-white border rounded-lg gap-x-2 sm:w-auto dark:hover:bg-gray-800 dark:bg-gray-900 hover:bg-gray-100 dark:text-gray-200 dark:border-gray-700">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" className="w-5 h-5 rtl:rotate-180">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 15.75L3 12m0 0l3.75-3.75M3 12h18" />
                </svg>


                <span>Vissza</span>
              </button>

              <Link to={'/'}>
                <button className="w-2/2 px-5 py-2 text-sm tracking-wide text-white transition-colors duration-200 bg-blue-500 rounded-lg shrink-0 sm:w-auto hover:bg-blue-600 dark:hover:bg-blue-500 dark:bg-blue-600">
                  A kezdőoldalra
                </button>
              </Link>
            </div>
          </div>
        </div>
      </section>
    </>

  )
}

export default Error
