import { useEffect, useState } from 'react';
import axios from 'axios';
import Modal from '../../components/Modal';
import { authByToken, fetchAuthentication } from '../../services/AuthService';
import { toast } from 'react-toastify';
import { Spinner } from '../../components/Spinner';

interface OpeningHour {
  id: string;
  day: string;
  open: string;
  close: string;
}


const Opening = () => {
  const [loading, setLoading] = useState(true);

  const [openingHours, setOpeningHours] = useState<OpeningHour[]>([]);
  const [openingModal, setOpeningModal] = useState(false);
  const [currentDay, setCurrentDay] = useState(0);
  const [adminLevel, setAdminLevel] = useState(0);

  console.log(`Welcome admin, with level: ${adminLevel}`);
  

  useEffect(() => {
    const admin = authByToken();

    if (admin) {
      setAdminLevel(admin.level ? admin.level : 0);
    }

  }, [])

  useEffect(() => {
    axios.get('/api/opening-hours')
      .then(res => setOpeningHours(res.data.openingHours))
      .catch(err => console.error(err))
      .finally(() => setLoading(false));
  }, []);


  const sendUpdatedOpeningHours = (e: React.FormEvent<HTMLFormElement>) => {
    e.preventDefault();
    const elements = e.currentTarget.elements as HTMLFormControlsCollection;

    const updatedOpeningHours = {
      csrf: (elements.namedItem("csrf") as HTMLInputElement)?.value,
      open: (elements.namedItem("open") as HTMLInputElement)?.value,
      close: (elements.namedItem("close") as HTMLInputElement)?.value,
    }

    fetchAuthentication.post(`/api/opening-hours/update/${openingHours[currentDay].id}`, updatedOpeningHours).then(res => {
      const { updated } = res.data;

      setOpeningHours(prevOpeningHours =>
        prevOpeningHours.map(openingHour =>
          openingHour.id === updated.id ? updated : openingHour
        )
      );


      toast.success('Nitvatartási idő sikeresen frissítve!');
      setOpeningModal(false);
    }).catch((err) => {
      console.error(err);
      toast.error('Általános szerver hiba!');
    })
  }

  return (
    <>
      {loading ? <Spinner /> : (
        <div  className="mt-5  w-screen">
          <div className="mx-auto lg:w-3/5 mt-20">
            <div className="mt-5 p-2 bg-light rounded text-dark">
              <h1 className='text-5xl font-bold'>Nyitvatartási idő</h1>
              <p className='mt-2'><em>Változtathatja az étterem nyitvatartási idejét. Ezáltal a felhasználó ezen időkereteken belül foglalhat asztalt kapacitás alapján.</em></p>
              <div className="p-4 mb-4 text-sm text-yellow-800 rounded-lg bg-yellow-50 dark:bg-gray-800 dark:text-yellow-300 mt-2" role="alert">
                <span className="font-medium">Figyelem!</span> Ez az oldal az alapvető nyitvatartást változtatja, ha csak kivételt szeretne hozzá adni a nyitvatartási időhöz akkor kattintson a "kivételek és ünnepnapok" navigációra.
              </div>
            </div>
            <div className="relative overflow-x-auto mt-5">
              <table className="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                <thead className="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                  <tr>
                    <th scope="col" className="px-6 py-3">Nap</th>
                    <th scope="col" className="px-6 py-3">Nyitás</th>
                    <th scope="col" className="px-6 py-3">Zárás</th>
                    <th scope="col" className="px-6 py-3">Műveletek</th>
                  </tr>
                </thead>
                <tbody>
                  {openingHours.map((day, index) => (
                    <tr key={day.id} className="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                      <th scope="row" className="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                        {day.day}
                      </th>

                      <td className="px-6 py-4">{day.open}</td>
                      <td className="px-6 py-4">{day.close}</td>
                      <td className="px-6 py-4">
                        <button className="bg-amber-500 hover:bg-amber-700 text-white font-bold py-2 px-4 rounded" onClick={() => {
                          setCurrentDay(index)
                          setOpeningModal(!openingModal)
                        }}>
                          Frissítés
                        </button>
                        <Modal show={openingModal} setShow={setOpeningModal} title='Nap frissítése' >
                          {openingHours[currentDay] && (
                            <form onSubmit={sendUpdatedOpeningHours}>
                              <div className="mb-4">
                                <label htmlFor="day" className="block text-gray-700">Nap</label>
                                <input
                                  type="text"
                                  id="day"
                                  name="day"
                                  defaultValue={openingHours[currentDay].day}
                                  className="mt-1 block w-full border border-gray-300 rounded-lg p-2"
                                  disabled
                                />
                              </div>
                              <div className="mb-4">
                                <label htmlFor="open" className="block text-gray-700">Nyitás</label>
                                <input
                                  type="time"
                                  id="open"
                                  name="open"
                                  defaultValue={openingHours[currentDay].open}
                                  className="mt-1 block w-full border border-gray-300 rounded-lg p-2"
                                />
                              </div>
                              <div className="mb-4">
                                <label htmlFor="close" className="block text-gray-700">Zárás</label>
                                <input
                                  type="time"
                                  id="close"
                                  name="close"
                                  defaultValue={openingHours[currentDay].close}

                                  className="mt-1 block w-full border border-gray-300 rounded-lg p-2"
                                />
                              </div>
                              <div className="mb-4">
                                <label htmlFor="close" className="block text-gray-700">Zárás</label>

                              </div>
                              <div className="flex justify-end">
                                <button
                                  type="button"
                                  className="mr-3 py-2 px-4 bg-gray-300 text-gray-800 rounded-lg hover:bg-gray-400"
                                  onClick={() => setOpeningModal(false)}
                                >
                                  Mégse
                                </button>
                                <button
                                  type="submit"
                                  className="py-2 px-4 bg-blue-600 text-white rounded-lg hover:bg-blue-700"
                                >
                                  Mentés
                                </button>
                              </div>
                            </form>
                          )}
                        </Modal>
                      </td>
                    </tr>
                  ))}
                </tbody>
              </table>
            </div>
          </div>
        </div >
      )}
    </>
  );
};

export default Opening;
