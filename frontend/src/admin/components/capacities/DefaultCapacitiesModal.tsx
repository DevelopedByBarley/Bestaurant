import React from 'react'
import Modal from '../../../components/Modal'
import CSFR from '../../../components/CSFR'
import Datepicker, { DateValueType } from 'react-tailwindcss-datepicker'
import { fetchAuthentication } from '../../../services/AuthService'
import { toast } from 'react-toastify'
import { DefaultCapacityTypes } from '../../pages/Capacities'


type DefaultCapacitiesModalTypes = {
  show: boolean;
  setShow: (show: boolean) => void;
  nextCapacity: DefaultCapacityTypes | null;
  setNextCapacity: (capacity: DefaultCapacityTypes | null) => void;
  defaultCapacity: DefaultCapacityTypes | null;
  handleCalendarChange: (dates: DateValueType | null) => void;
  calendar: DateValueType;
};

const DefaultCapacitiesModal = ({ show, setShow, nextCapacity, setNextCapacity, defaultCapacity, handleCalendarChange, calendar }: DefaultCapacitiesModalTypes) => {

  
  const handleDefaultCapacity = (e: React.FormEvent<HTMLFormElement>) => {
    e.preventDefault();
    const elements = e.currentTarget.elements as HTMLFormControlsCollection;

    const newDefaultCapacity = {
      csrf: (elements.namedItem("csrf") as HTMLInputElement)?.value,
      date: calendar?.startDate,
      capacity: (elements.namedItem("capacity") as HTMLInputElement)?.value,
    }

    if (nextCapacity) {
      fetchAuthentication.post(`/api/capacity/update/default/${nextCapacity.id}`, newDefaultCapacity).then(res => {
        const { updated } = res.data;
        setNextCapacity(updated);
        toast.success('Soron következő kapacitás sikeresen frissítve!');
      })
    } else {

      if (calendar?.startDate && defaultCapacity?.validFrom) {
        if (calendar?.startDate <= defaultCapacity?.validFrom) return toast.warning('Az új alap kapacitás érvényességének kezdet nem lehet kissebb vagy egyenlő mint a soron következő kapacitásáé.');
      }

      fetchAuthentication.post('/api/capacity/new/default', newDefaultCapacity).then((res) => {
        const { lastInsertedId } = res.data;

        const newDefaultCapacityResult: DefaultCapacityTypes = {
          id: Number(lastInsertedId),
          created_at: new Date().toISOString().split('T')[0],
          validFrom: String(calendar?.startDate),
          capacity: Number(newDefaultCapacity.capacity),
        };

        if (defaultCapacity) setNextCapacity(newDefaultCapacityResult);

        toast.success('Soron következő kapacitás sikeresen hozzáadva!');
      });
    }

    setShow(false);
  }

  return (
    <Modal show={show} setShow={setShow} title={nextCapacity ? 'Alap kapacitás frissítése' : 'Alap kapacitás beállítása'}>
      <div className='font-bold'>
        A jelenlegi érték <span className='bg-cyan-500  text-white py-2 px-4 rounded-full'>{defaultCapacity ? defaultCapacity.capacity : ''}</span> amely érvényes <span className='bg-cyan-500  text-white py-2 px-4 rounded-full'>{defaultCapacity ? defaultCapacity.validFrom : ''}</span>
      </div>
      <form className='py-5' onSubmit={handleDefaultCapacity}>
        <CSFR dependency={[]} />
        {nextCapacity?.capacity ? (
          <div className='mt-2'>
            <p>
              Soron következő kapacitás érvényességének kezdete: <span className='text-cyan-500 font-bold  text-lg underline'>{nextCapacity?.validFrom} </span> <br /> amely értéke: <span className='text-cyan-500 font-bold  text-lg  underline'> {nextCapacity.capacity}</span>
            </p>
            <label className='mt-7 mb-1 block'>Új alap kapacitás érvényességének kezdete</label>
            <div className="border border-neutral-900 rounded-xl xl:w-4/5 relative">
              <Datepicker useRange={false} asSingle={true} value={calendar} minDate={new Date()} onChange={handleCalendarChange} />
            </div>
            <div>
              <label className='mt-7 mb-1 block '>Új alap kapacitás értéke</label>
              <input type="number" min={1} required name="capacity" defaultValue={nextCapacity ? nextCapacity.capacity : ''} aria-describedby="helper-text-explanation" placeholder='Kapacitás értéke...'
                className=" xl:w-4/5 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
              />
            </div>
            <button className="btn-yellow mt-5" type='submit'>Frissít</button>
          </div>

        ) : (
          <>
            <label className='mt-7 mb-1 block'>Új alap kapacitás érvényességének kezdete</label>
            <div className="border border-neutral-900 rounded-xl xl:w-4/5 relative">
              <Datepicker useRange={false} asSingle={true} value={calendar} minDate={new Date()} onChange={handleCalendarChange} />
            </div>
            <div>
              <label className='mt-7 mb-1 block '>Új alap kapacitás értéke</label>
              <input type="number" min={1} required name="capacity" aria-describedby="helper-text-explanation" placeholder='Kapacitás értéke...'
                className=" xl:w-4/5 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
              />
            </div>
            <button className="btn-green mt-5" type='submit'>Elküld</button>
          </>
        )}
      </form>
    </Modal>
  )
}

export default DefaultCapacitiesModal
