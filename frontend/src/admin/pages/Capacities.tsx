import { useEffect, useState } from 'react';
import Alert from '../../components/Alert';
import axios from 'axios';
import { authByToken } from '../../services/AuthService';
import Error from '../../pages/Error';
import { toast } from 'react-toastify';
import { Spinner } from '../../components/Spinner';
import Modal from '../../components/Modal';
import Pagination from '../../components/Pagination';
import { CapacitiesTable } from '../capacities/CapacitiesTable';
import { SearchCapacities } from '../capacities/SearchCapacities';

export type CapacityTypes = {
  capacity: number,
  created_at: string,
  date: string,
  id: number,
  is_default: boolean
}


const Capacities = () => {
  const [defaultCapacity, setDefaultCapacity] = useState(null);
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
  const [search, setSearch] = useState('')
  const [modalStatus, setModalStatus] = useState<'default' | 'exception' | 'update' | 'delete' | ''>('');


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
    axios.get(url)
      .then(res => {
        const { defaultCapacity, exceptions } = res.data;
        setDefaultCapacity(defaultCapacity.capacity);
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
                <div>Alap Kapacitás beállítása</div>
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

            {defaultCapacity !== null && (
              <div className='p-5 my-16  shadow-transparent  text-center'>
                <h1 className="font-bold text-3xl mt-10">Alap kapacitás: <span className=' bg-cyan-500 text-white p-3 rounded-full'>{defaultCapacity}</span></h1>
                <button className='btn-dark mt-10' onClick={() => { setShow(true); setModalStatus('default'); }}>Alap Kapacitás beállítása</button>
                <button className='btn-dark mt-10' onClick={() => { setShow(true); setModalStatus('exception'); }}>Új kivétel hozzáadása</button>
              </div>
            )}
            {capacities.length !== null && (
              <>
                <div className="flex justify-between">
                  <SearchCapacities search={search} setSearch={setSearch} />
                  <Pagination currentPage={currentPage} setCurrentPage={setCurrentPage} numOfPage={numOfPage} />
                </div>
                <CapacitiesTable capacities={capacities} setCapacities={setCapacities} sortConfig={sortConfig} requestSort={requestSort} show={show} setShow={setShow} modalStatus={modalStatus} setModalStatus={setModalStatus}/>
              </>
            )}
          </div>
        ) : (<Error />)
      ) : <Spinner />}
    </>
  );
}

export default Capacities;
