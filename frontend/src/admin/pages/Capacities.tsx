import { useEffect, useState } from 'react';
import Alert from '../../components/Alert';
import { authByToken, fetchAuthentication } from '../../services/AuthService';
import Error from '../../pages/Error';
import { toast } from 'react-toastify';
import { Spinner } from '../../components/Spinner';
import Modal from '../../components/Modal';
import Pagination from '../../components/Pagination';
import { CapacitiesTable } from '../components/capacities/CapacitiesTable';
import { SearchCapacities } from '../components/capacities/SearchCapacities';
import Datepicker, { DateValueType } from 'react-tailwindcss-datepicker';
import CSFR from '../../components/CSFR';

export type CapacityTypes = {
  capacity: number,
  created_at: string,
  date: string,
  id: number,
  is_default: boolean
}

export type DefaultCapacityTypes = {
  capacity: number,
  created_at: string,
  date: string,
  id: number,
  validFrom: string
}


const Capacities = () => {
  const [defaultCapacity, setDefaultCapacity] = useState<DefaultCapacityTypes | null>(null);
  const [nextCapacity, setNextCapacity] = useState<DefaultCapacityTypes | null>(null);
  const [capacities, setCapacities] = useState<CapacityTypes[]>([]);
  const [adminLevel, setAdminLevel] = useState<number | null>(null);
  const [loading, setLoading] = useState(true);
  const [show, setShow] = useState(false);
  const [currentPage, setCurrentPage] = useState<number>(1);
  const [numOfPage, setNumOfPage] = useState(0);
  const [sortConfig, setSortConfig] = useState<{ key: keyof CapacityTypes | null, direction: 'asc' | 'desc' | null }>({
    key: null,
    direction: null,
  });
  const [calendar, setCalendar] = useState<DateValueType>({
    startDate: new Date().toISOString().split('T')[0],
    endDate: new Date().toISOString().split('T')[0]
  });
  const [search, setSearch] = useState('')
  const [modalStatus, setModalStatus] = useState<'default' | 'exception' | 'update' | 'delete' | ''>('');

  const handleCalendarChange = (dates: DateValueType | null) => {
    setCurrentPage(1);
    setCalendar(dates);
  };


  const newDefaultCapacity = (e: React.FormEvent<HTMLFormElement>) => {

    e.preventDefault();


    const elements = e.currentTarget.elements as HTMLFormControlsCollection;

    const newDefaultCapacity = {
      csrf: (elements.namedItem("csrf") as HTMLInputElement)?.value,
      date: calendar?.startDate,
      capacity: (elements.namedItem("capacity") as HTMLInputElement)?.value,
    }

    console.log(newDefaultCapacity);
  }

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

  useEffect(() => {
    const admin = authByToken();
    if (admin) {
      setAdminLevel(admin.level);
    }
    const sortParam = sortConfig.key ? `&sort=${sortConfig.key}&order=${sortConfig.direction ? sortConfig.direction : ''}` : '';
    const searchParam = search !== '' ? `&search=${search}` : '';


    const url = `/api/capacity?offset=${currentPage}${sortParam}${searchParam}`
    fetchAuthentication.get(url)
      .then(res => {
        console.log(res.data)
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

  return (
    <>
      {!loading ? (
        adminLevel && adminLevel > 1 ? (
          <div className="container mx-auto my-16">
            {modalStatus === 'default' && (
              <Modal show={show} setShow={setShow} title='Alap kapacitás beállítása'>
                <form className='py-5' onSubmit={newDefaultCapacity}>
                  <CSFR dependency={[]} />
                  <div className='font-bold'>Alap Kapacitás beállítása, a jelenlegi érték <span className='bg-cyan-500  text-white py-2 px-4 rounded-full'>{defaultCapacity ? defaultCapacity.capacity : ''}</span></div>
                  {nextCapacity?.capacity && (
                    <div className='mt-2'>
                      Soron következő kapacitás érvényességének kezdete: <span className='text-cyan-500 font-bold  text-lg underline'>{nextCapacity?.validFrom} </span> <br /> amely értéke: <span className='text-cyan-500 font-bold  text-lg  underline'> {nextCapacity.capacity}</span>
                    </div>
                  )}

                  <label className='mt-7 mb-1 block'>Új alap kapacitás érvényességének kezdete</label>
                  <div className="border border-neutral-900 rounded-xl xl:w-4/5 relative">
                    <Datepicker
                      useRange={false}
                      asSingle={true}
                      value={calendar}
                      minDate={new Date()}
                      onChange={handleCalendarChange}
                    />
                  </div>
                  <div>
                    <label className='mt-7 mb-1 block '>Új alap kapacitás értéke</label>
                    <input
                      type="number"
                      min={1}
                      required
                      name="capacity"
                      id="number-input"
                      aria-describedby="helper-text-explanation"
                      placeholder='Kapacitás értéke...'
                      className=" xl:w-4/5 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                    />
                  </div>
                  <button className="btn-green mt-5" type='submit'>Elküld</button>
                </form>
              </Modal>
            )}

            {modalStatus === 'exception' && (
              <Modal show={show} setShow={setShow} title='Kivétel hozzáadása'>
                <div>Kapacitás beállítása</div>
              </Modal>
            )}
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

            {defaultCapacity && defaultCapacity.capacity !== null && (
              <div className='p-5 my-16  shadow-transparent  text-center'>
                <h1 className="font-bold text-3xl mt-10">Alap kapacitás: <span className=' bg-cyan-500 text-white px-5 py-2 rounded-full'>{defaultCapacity.capacity}</span></h1>
                <button className='btn-dark mt-10' onClick={() => { setShow(true); setModalStatus('default'); }}>Alap Kapacitás beállítása</button>
                <button className='btn-light xl:mt-10' onClick={() => { setShow(true); setModalStatus('exception'); }}>Új kivétel hozzáadása</button>
              </div>
            )}
            {capacities.length !== null && (
              <>
                <div className="flex justify-between">
                  <SearchCapacities search={search} setSearch={setSearch} />
                  <Pagination currentPage={currentPage} setCurrentPage={setCurrentPage} numOfPage={numOfPage} />
                </div>
                <CapacitiesTable capacities={capacities} setCapacities={setCapacities} sortConfig={sortConfig} requestSort={requestSort} show={show} setShow={setShow} modalStatus={modalStatus} setModalStatus={setModalStatus} />
              </>
            )}
          </div>
        ) : (<Error />)
      ) : <Spinner />}
    </>
  );
}

export default Capacities;
