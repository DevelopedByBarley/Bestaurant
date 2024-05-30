import { useEffect, useState } from 'react';
import Alert from '../../components/Alert';
import axios from 'axios';
import { authByToken } from '../../services/AuthService';
import Error from '../../pages/Error';

const Capacities = () => {
  const [capacity, setCapacity] = useState(null);
  const [defaultActive, setDefaultActive] = useState(false);
  const [adminLevel, setAdminLevel] = useState<number | null>(null);


  useEffect(() => {
    const admin = authByToken();
    if (admin) {
      setAdminLevel(admin.level)
    }

    axios.get('/api/capacity').then(res => {
      const { data } = res.data;
      setCapacity(data.capacity);
    });

  }, []);

  return (
    <>
      {adminLevel && adminLevel > 1 ? (
        <div className="container mx-auto my-16">
          <h1 className='text-4xl font-extrabold text-center'>
            Kapacitás beállítása
            <span className='mx-3 inline-block relative bottom-1'>
              <Alert
                title='Info'
                content='Beállíthatja tetszés szerint az étterem kapacitását, azaz hogy az étterem hány vendéget tud fogadni.
              Ezt egy állandó kapacitás kereteiben is meg tudja adni ami egy állandó érték, illetve hozzá tud adni bizonyos
              napokra kivételeket is. Ez alapján az érték alapján figyeli a rendszer hogy egy intervallumot maximum hány ember foglalhat be.'
              />
            </span>
          </h1>

          {capacity !== null ? (
            <form className="w-full max-w-52">
              <h1 className='font-bold text-2xl'>Alap kapacitás</h1>
              <div className="flex items-center border-b border-teal-500 py-2">
                <input
                  disabled={!defaultActive}
                  defaultValue={capacity}
                  className={`appearance-none bg-transparent border-none w-full text-gray-700  ${!defaultActive ? 'bg-gray-200' : ''} py-3 mr-3  px-2 leading-tight focus:outline-none`}
                  type="number"
                  aria-label="Capacity"
                />

                {defaultActive ? (
                  <>
                    <button
                      onClick={() => setDefaultActive(true)}
                      className="mx-2 flex-shrink-0 bg-yellow-500 hover:bg-yellow-700 border-yellow-500 hover:border-yellow-700 text-sm border-4 text-white py-1 px-2 rounded"
                      type="button"
                    >
                      Véglegesít
                    </button>
                    <button
                      onClick={() => setDefaultActive(false)}
                      className="flex-shrink-0 border-transparent border-4 text-teal-500 hover:bg-gray-300  hover:text-teal-800 text-sm py-1 px-2 rounded"
                      type="button"
                    >
                      x
                    </button>
                  </>
                ) : (
                  <button
                    onClick={() => setDefaultActive(true)}
                    className="flex-shrink-0 bg-teal-500 hover:bg-teal-700 border-teal-500 hover:border-teal-700 text-sm border-4 text-white py-1 px-2 rounded"
                    type="button"
                  >
                    Megváltoztat
                  </button>
                )}

              </div>
            </form>
          ) : (
            <div>Loading...</div> // Adat betöltése közben egy egyszerű "Loading..." üzenet
          )}
        </div>
      ) : (< Error/>)}
    </>
  );
}

export default Capacities;
