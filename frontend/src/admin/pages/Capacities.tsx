import { useEffect, useState } from 'react';
import Alert from '../../components/Alert';
import axios from 'axios';
import { authByToken } from '../../services/AuthService';
import Error from '../../pages/Error';
import { toast } from 'react-toastify';
import { Spinner } from '../../components/Spinner';

const Capacities = () => {
  const [defaultCapacity, setDefaultCapacity] = useState(null);
  const [capacities, setCapacities] = useState([]);
  const [adminLevel, setAdminLevel] = useState<number | null>(null);
  const [loading, setLoading] = useState(true);

  console.log(capacities);
  

  useEffect(() => {
    const admin = authByToken();
    if (admin) {
      setAdminLevel(admin.level);
    }

    axios.get('/api/capacity')
      .then(res => {
        const { defaultCapacity, exceptions } = res.data;
        setDefaultCapacity(defaultCapacity.capacity);
        setCapacities(exceptions);
      })
      .catch(err => {
        toast.error('Általános szerver hiba.');
        console.error(err);
      })
      .finally(() => {
        setLoading(false);
      });
  }, []);

  return (
    <>
      {!loading ? (
        adminLevel && adminLevel > 1 ? (
          <div className="container mx-auto my-16">
            <h1 className="text-4xl font-extrabold text-center">
              Kapacitás beállítása
              <span className="mx-3 inline-block relative bottom-1">
                <Alert
                  title="Info"
                  content="Beállíthatja tetszés szerint az étterem kapacitását, azaz hogy az étterem hány vendéget tud fogadni.
                  Ezt egy állandó kapacitás kereteiben is meg tudja adni ami egy állandó érték, illetve hozzá tud adni bizonyos
                  napokra kivételeket is. Ez alapján az érték alapján figyeli a rendszer hogy egy intervallumot maximum hány ember foglalhat be."
                />
              </span>
            </h1>

            {defaultCapacity !== null && (
              <form className='bg-red-400'>
                <h1 className="font-bold text-4xl mt-10">Alap kapacitás: {defaultCapacity}</h1>
              
              </form>
            )}
          </div>
        ) : (<Error />)
      ) : <Spinner />}
    </>
  );
}

export default Capacities;
