import { useContext, useEffect, useState } from 'react';
import { authByToken, fetchAuthentication } from '../../services/AuthService';
import Datepicker, { DateValueType } from 'react-tailwindcss-datepicker';
import Pagination from '../../components/Pagination';
import { ReservationsTypes } from '../../types/ReservationsTypes';

import NoReservationHeader from '../components/reservations/NoReservationHeader';
import ReservationsTable from '../components/reservations/ReservationsTable';
import SearchBar from '../components/reservations/SearchBar';
import Alert from '../../components/Alert';
import { ModalContext } from '../../context/ModalContext';
import Reservation from '../../pages/Reservation';

const Reservations = () => {
  const { modal, setModal } = useContext(ModalContext);
  const [reservations, setReservations] = useState<ReservationsTypes[]>([]);
  const [numOfPage, setNumOfPage] = useState<number>(0);
  const [currentPage, setCurrentPage] = useState<number>(1);
  const [search, setSearch] = useState('');
  const [category, setCategory] = useState('');
  const [calendar, setCalendar] = useState<DateValueType>({
    startDate: null,
    endDate: null
  });
  const [sortConfig, setSortConfig] = useState<{ key: keyof ReservationsTypes | null, direction: 'asc' | 'desc' | null }>({
    key: null,
    direction: null,
  });
  const handleCalendarChange = (dates: DateValueType | null) => {
    setCurrentPage(1);
    setCalendar(dates);
  };


  useEffect(() => {
    const fetchReservations = async () => {

      authByToken();

      const sortParam = sortConfig.key ? `&sort=${sortConfig.key}&order=${sortConfig.direction ? sortConfig.direction : ''}` : '';
      const dateParam = calendar?.startDate ? `&date=${calendar.startDate}` : '';
      const searchParam = search !== '' ? `&category=${category}&search=${search}` : '';


      const url = `/api/reservation?offset=${currentPage}${dateParam}${sortParam}${searchParam}`;

      try {
        const res = await fetchAuthentication.get(url);
        const { data } = res.data;
        setReservations(data.pages);
        setNumOfPage(data.numOfPage);
      } catch (err) {
        console.error("Error fetching reservations:", err);
      }
    };

    fetchReservations();
  }, [currentPage, calendar, sortConfig, search, category, modal]);

  const requestSort = (key: keyof ReservationsTypes | null) => {
    let direction: 'asc' | 'desc' | null = 'desc';
    if (sortConfig.key === key && sortConfig.direction === 'desc') {
      direction = 'asc';
    } else if (sortConfig.key === key && sortConfig.direction === 'asc') {
      direction = null;
      key = null
    }
    setSortConfig({ key, direction });
  };

  return (
    <div className="container mx-auto my-16">
      {modal && <Reservation />}

      <div className="grid grid-cols-3">
        <div className='col-span-3 text-center mb-10 flex items-center justify-center flex-col space-y-5'>
          <h1 className='font-extrabold text-4xl'>Összes foglalás
            <span className='mx-3 inline-block relative bottom-1'>
              <Alert title='Info'
                content='Áttekintheti és kezelheti az étterem foglalásait. A rendszer értesítést küld a műveletek végrehajtásakor a foglaló e-mail címére!'
              />
            </span>
          </h1>
          <div className="flex">
            <button
              onClick={() => {
                setCalendar({ startDate: new Date().toISOString().split('T')[0], endDate: new Date().toISOString().split('T')[0] });
              }}
              className='btn-dark rounded-3xl'
            >
              Megnézem a mai napot
            </button>
            <button
              onClick={() => {
                setModal(true);
              }}
              className='btn-green rounded-3xl'
            >
              Vendég érkezett
            </button>
          </div>
        </div>


        <div className="w-full col-span-3 p-2">
          <div className="border border-neutral-900 rounded-xl xl:w-1/5 mx-auto relative z-40">
            <Datepicker
              useRange={false}
              asSingle={true}
              value={calendar}
              onChange={handleCalendarChange}
            />
          </div>
        </div>



        <div className='container w-full col-span-3 xl:flex justify-between p-2'>
          <div className='flex items-end xl:w-4/5 my-5'>
            <SearchBar search={search} setSearch={setSearch} category={category} setCategory={setCategory} />
          </div>
          {calendar?.startDate === null ? (
            <div className='my-5 flex items-end justify-center'>
              <Pagination numOfPage={numOfPage} currentPage={currentPage} setCurrentPage={setCurrentPage} />
            </div>
          ) : (
            <div className="flex flex-col">
              <div className='font-extrabold mb-2 text-center'>Foglalások erre a napra: <span className='text-teal-400'>{calendar?.startDate?.toString()}</span></div>
              {reservations.length !== 0 && <Pagination numOfPage={numOfPage} currentPage={currentPage} setCurrentPage={setCurrentPage} />}
            </div>
          )}
        </div>




        <div className="container w-full col-span-3 mb-10 mt-5">
          <div className="relative overflow-x-auto shadow-md sm:rounded-xl min-h-80">
            {reservations.length === 0 ? (
              <NoReservationHeader />
            ) : (
              <ReservationsTable reservations={reservations} setReservations={setReservations} sortConfig={sortConfig} requestSort={requestSort} />

            )}
          </div>
        </div>

      </div>
    </div>
  );
};

export default Reservations;
