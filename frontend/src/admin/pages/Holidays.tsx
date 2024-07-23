import { useEffect, useState } from 'react'
import { authByToken } from '../../services/AuthService';
import axios from 'axios';
import Pagination from '../../components/Pagination';
import { AddHolidayModal } from '../components/holidays/AddHolidayModal';

type HolidayType = {
  id: number;
  date: string;
  open: string;
  close: string;
  description: string;

}

const Holidays = () => {

  const [adminLevel, setAdminLevel] = useState(0);
  const [holidays, setHolidays] = useState<HolidayType[]>([]);
  const [currentPage, setCurrentPage] = useState(1);
  const [numOfPage, setNumOfPage] = useState(0);
  const [show, setShow] = useState(false);


  useEffect(() => {
    const admin = authByToken();

    if (admin) {
      setAdminLevel(admin.level ? admin.level : 0);
    }

  }, [])



  useEffect(() => {
    axios.get(`/api/holidays?offset=${currentPage}`)
      .then(res => {
        console.log(res.data.holidays)
        const { numOfPage, pages } = res.data.holidays;
        setNumOfPage(numOfPage);
        setHolidays(pages)
      })
      .catch(err => console.error(err));
  }, [currentPage]);

  console.log(holidays);

  return (
    <div className='container mx-auto'>
      <div className="mt-10">
        <div className="mt-5 p-2 bg-light rounded text-dark">
          <h1 className='text-5xl font-bold'>Ünnepnapok és kivételek</h1>
          <p className='mt-2'>
            <em>
              Hozzáadhat ünnepnapokat és kivételeket a nyitvatartáshoz, itt az adott napon megváltoztathatja a nyitvatartását az étteremnek vagy be is zárhatja azt.
            </em>
          </p>

        </div>
        <div className="text-center mb-14">
          <button className='btn-dark mt-10' onClick={() => setShow(!show)}>Kivétel hozzáadása</button>
        </div>
        <Pagination currentPage={currentPage} setCurrentPage={setCurrentPage} numOfPage={numOfPage} />
        <div className="relative overflow-x-auto shadow-md sm:rounded-lg mt-10">
        </div>
        <table className="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
          <thead className="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
            <tr>
              <th scope="col" className="px-6 py-3">
                id
              </th>
              <th scope="col" className="px-6 py-3">
                Dátum
              </th>
              <th scope="col" className="px-6 py-3">
                Nyitás
              </th>
              <th scope="col" className="px-6 py-3">
                Zárás
              </th>
              <th scope="col" className="px-6 py-3">
                Leírás
              </th>
              <th scope="col" className="px-6 py-3">
                Műveletek
              </th>
            </tr>
          </thead>
          <tbody>

            {
              holidays.map((holiday) => {
                return (
                  <>
                    <tr className="odd:bg-white odd:dark:bg-gray-900 even:bg-gray-50 even:dark:bg-gray-800 border-b dark:border-gray-700">
                      <th scope="col" className="px-6 py-3">
                        {holiday.id}
                      </th>
                      <th scope="col" className="px-6 py-3">
                        {holiday.date}
                      </th>
                      <th scope="col" className="px-6 py-3">
                        {holiday.open ? holiday.open : 'Zárva'}
                      </th>
                      <th scope="col" className="px-6 py-3">
                        {holiday.close ? holiday.close : 'Zárva'}
                      </th>
                      <th scope="col" className="px-6 py-3">
                        {holiday.description}
                      </th>
                      <th scope="col" className="px-6 py-3">
                        <div className="inline-flex rounded-md shadow-sm  gap-1" role="group">
                          <button type="button" className="px-4 py-2 text-sm font-medium text-gray-900 bg-white border border-gray-200 rounded-s-lg hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-2 focus:ring-blue-700 focus:text-blue-700 dark:bg-gray-800 dark:border-gray-700 dark:text-white dark:hover:text-white dark:hover:bg-gray-700 dark:focus:ring-blue-500 dark:focus:text-white">
                            Megtekintés
                          </button>
                          <button type="button" className="px-4 py-2 text-sm font-medium text-gray-900 bg-white border-t border-b border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-2 focus:ring-blue-700 focus:text-blue-700 dark:bg-gray-800 dark:border-gray-700 dark:text-white dark:hover:text-white dark:hover:bg-gray-700 dark:focus:ring-blue-500 dark:focus:text-white">
                            Frissítés
                          </button>
                          <button type="button" className="px-4 py-2 text-sm font-medium text-gray-900 bg-white border border-gray-200 rounded-e-lg hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-2 focus:ring-blue-700 focus:text-blue-700 dark:bg-gray-800 dark:border-gray-700 dark:text-white dark:hover:text-white dark:hover:bg-gray-700 dark:focus:ring-blue-500 dark:focus:text-white">
                            Törlés
                          </button>
                        </div>

                      </th>
                    </tr>
                  </>
                )
              })
            }
          </tbody>
        </table>
      </div>

      <AddHolidayModal show={show} setShow={setShow} />

    </div>
  )
}

export default Holidays
