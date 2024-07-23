import { useEffect, useState } from 'react';
import { authByToken, fetchAuthentication } from '../../services/AuthService';
import Error from '../../pages/Error';
import { toast } from 'react-toastify';
import { Spinner } from '../../components/Spinner';
import Pagination from '../../components/Pagination';
import { CapacitiesTable } from '../components/capacities/CapacitiesTable';
import { SearchCapacities } from '../components/capacities/SearchCapacities';
import { DateValueType } from 'react-tailwindcss-datepicker';
import DefaultCapacitiesModal from '../components/capacities/DefaultCapacitiesModal';
import ExceptionCapacityModal from '../components/capacities/ExceptionCapacityModal';
import CapacitiesInfoAlert from '../components/capacities/CapacitiesInfoAlert';

export type CapacityTypes = {
  capacity: number,
  created_at: string,
  date: string,
  id: number,
}

export type DefaultCapacityTypes = {
  capacity: number,
  created_at: string,
  id: number,
  validFrom: string
}


const Capacities = () => {
  const [loading, setLoading] = useState(true);
  const [show, setShow] = useState(false);
  const [search, setSearch] = useState('')
  const [modalStatus, setModalStatus] = useState<'default' | 'exception' | 'update' | 'delete' | ''>('');
  const [numOfPage, setNumOfPage] = useState(0);

  const [defaultCapacity, setDefaultCapacity] = useState<DefaultCapacityTypes | null>(null);
  const [nextCapacity, setNextCapacity] = useState<DefaultCapacityTypes | null>(null);
  const [capacities, setCapacities] = useState<CapacityTypes[]>([]);
  const [adminLevel, setAdminLevel] = useState<number | null>(null);
  const [currentPage, setCurrentPage] = useState<number>(1);
  const [sortConfig, setSortConfig] = useState<{ key: keyof CapacityTypes | null, direction: 'asc' | 'desc' | null }>({
    key: null,
    direction: null,
  });
  const [calendar, setCalendar] = useState<DateValueType>({
    startDate: nextCapacity?.validFrom ? nextCapacity?.validFrom : new Date().toISOString().split('T')[0],
    endDate: nextCapacity?.validFrom ? nextCapacity?.validFrom : new Date().toISOString().split('T')[0],
  });

  

  // SORTING CAPACITY DATA 

  const requestSort = (key: keyof CapacityTypes | null) => {
    let direction: 'asc' | 'desc' | null = 'desc';
    if (sortConfig.key === key && sortConfig.direction === 'desc') {
      direction = 'asc';
    } else if (sortConfig.key === key && sortConfig.direction === 'asc') {
      direction = null;
      key = null
    }
    setSortConfig({ key, direction });
  };


  // HANDLING CALDENDAR CHANGE 

  const handleCalendarChange = (dates: DateValueType | null) => {
    setCurrentPage(1);

    if (dates?.startDate && defaultCapacity?.validFrom) {
      if (dates?.startDate <= defaultCapacity?.validFrom) return toast.warning('Az új kivétel kapacitás érvényességének kezdet nem lehet kissebb vagy egyenlő mint a soron következő kapacitásáé.');
    }

    setCalendar(dates);
  };


  useEffect(() => {
    // CHECK IS ADMIN
    const admin = authByToken();
    if (admin) {
      setAdminLevel(admin.level);
    }

    // MAKING PARAMS FOR QUERY STRING
    const sortParam = sortConfig.key ? `&sort=${sortConfig.key}&order=${sortConfig.direction ? sortConfig.direction : ''}` : '';
    const searchParam = search !== '' ? `&search=${search}` : '';

    const url = `/api/capacity?offset=${currentPage}${sortParam}${searchParam}`
    // GET CAPACITIES DATA BY PARAMS
    fetchAuthentication.get(url)
      .then(res => {
        const { defaultCapacity, nextDefaultCapacity, exceptions } = res.data;
        setDefaultCapacity(defaultCapacity);
        if (nextDefaultCapacity) setNextCapacity(nextDefaultCapacity);
        setCapacities(exceptions.pages);
        setNumOfPage(exceptions.numOfPage)
      })
      .catch(err => {
        toast.error('Általános szerver hiba.');
        console.error(err);
      })
      .finally(() => {
        setLoading(false);
      });

  }, [currentPage, sortConfig.direction, sortConfig.key, search]);

  // IF NEXT CAPACITY EXIST SET THE CALENDAR FOR THE NEXT CAPACITY FORM
  useEffect(() => {
    if (nextCapacity?.validFrom) {
      setCalendar({
        startDate: new Date(nextCapacity.validFrom).toISOString().split('T')[0],
        endDate: new Date(nextCapacity.validFrom).toISOString().split('T')[0],
      });
    }
  }, [nextCapacity]);




  return (
    <>
      {!loading ? (
        adminLevel && adminLevel > 1 ? (
          <div className="container mx-auto my-16">
            {modalStatus === 'default' && (
              <DefaultCapacitiesModal
                show={show}
                setShow={setShow}
                nextCapacity={nextCapacity}
                setNextCapacity={setNextCapacity}
                defaultCapacity={defaultCapacity}
                handleCalendarChange={handleCalendarChange}
                calendar={calendar} />
            )}

            {modalStatus === 'exception' && (
              <ExceptionCapacityModal
                show={show}
                setShow={setShow}
                capacities={capacities}
                setCapacities={setCapacities}
                handleCalendarChange={handleCalendarChange}
                calendar={calendar}
              />
            )}
            <h1 className="text-4xl font-extrabold text-center">
              Kapacitás beállítása
              <span className="mx-3 inline-block relative bottom-1">
                <CapacitiesInfoAlert />
              </span>
            </h1>


            <div className='p-5 my-16  shadow-transparent  text-center'>
              <h1 className="font-bold text-3xl mt-10">Kapacitás mai napra: <span className=' bg-cyan-500 text-white px-5 py-2 rounded-full'>{defaultCapacity?.capacity}</span></h1>
              <button className='btn-dark mt-10' onClick={() => { setShow(true); setModalStatus('default'); }}>Alap Kapacitás beállítása</button>
              <button className='btn-light xl:mt-10' onClick={() => { setShow(true); setModalStatus('exception'); }}>Új kivétel hozzáadása</button>
            </div>
            {capacities.length !== null && (
              <>
                <div className="lg:flex space-y-10 lg:space-y-0 justify-between">
                  <SearchCapacities search={search} setSearch={setSearch} />
                  <Pagination currentPage={currentPage} setCurrentPage={setCurrentPage} numOfPage={numOfPage} />
                </div>
                <div className='relative overflow-x-auto shadow-md mt-5'>
                  <CapacitiesTable capacities={capacities} setCapacities={setCapacities} sortConfig={sortConfig} requestSort={requestSort} show={show} setShow={setShow} modalStatus={modalStatus} setModalStatus={setModalStatus} />
                </div>
              </>
            )}
          </div>
        ) : (<Error />)
      ) : <Spinner />}
    </>
  );
}

export default Capacities;
