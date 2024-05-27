import React, { useEffect } from 'react'
import { authByToken } from '../../services/AuthService'
import Alert from '../../components/Alert';

const Capacities = () => {

  // 1. DEFAULT CAPACITY LEKÉRÉSE ÉS BETÖLTÉSE INPUTBA
  // 2. OnChange-re dobja fel a műveleti gombokat mint frissít, és vissza
  // 3. A kivételek lekérése és betöltése pagination-ba
  // 4. Kivétel műveletek 
  useEffect(() => {
    authByToken();
  }, [])

  return (
    <div className="container mx-auto  my-16">
      <h1 className='text-4xl font-extrabold text-center'>Kapacitás beállítása
        <span className='mx-3 inline-block relative bottom-1'>
          <Alert title='Info' content='Beállíthatja tetszés szerint az étterem kapacitását, azaz hogy az étterem hány vendéget tud fogadni.
        Ezt egy állandó kapacitás kereteiben is meg tudja adni ami egy állandó érték, illetve hozzá tud adni bizonyos
        napokra kivételeket is. Ez alapján az érték alapján figyeli a rendszer hogy egy intervallumot maximum hány ember foglalhat be.'/>
        </span>
      </h1>

      <form className="w-full max-w-sm">
        <div className="flex items-center border-b border-teal-500 py-2">
          <input className="appearance-none bg-transparent border-none w-full text-gray-700 mr-3 py-1 px-2 leading-tight focus:outline-none" type="text" placeholder="Jane Doe" aria-label="Full name" />
            <button className="flex-shrink-0 bg-teal-500 hover:bg-teal-700 border-teal-500 hover:border-teal-700 text-sm border-4 text-white py-1 px-2 rounded" type="button">
              Sign Up
            </button>
            <button className="flex-shrink-0 border-transparent border-4 text-teal-500 hover:text-teal-800 text-sm py-1 px-2 rounded" type="button">
              Cancel
            </button>
        </div>
      </form>

    </div>
  )
}

export default Capacities
