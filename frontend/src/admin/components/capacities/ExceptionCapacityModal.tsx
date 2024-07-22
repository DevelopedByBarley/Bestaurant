import Modal from '../../../components/Modal'
import CSFR from '../../../components/CSFR'
import Datepicker, { DateValueType } from 'react-tailwindcss-datepicker'
import { toast } from 'react-toastify'
import { fetchAuthentication } from '../../../services/AuthService'
import { CapacityTypes } from './CapacitiesTable'
import { Dispatch, SetStateAction } from 'react'

type ExceptionCapacityModalTypes = {
  show: boolean;
  setShow: (show: boolean) => void;
  capacities: CapacityTypes[];
  setCapacities: Dispatch<SetStateAction<CapacityTypes[]>>;
  handleCalendarChange: (dates: DateValueType | null) => void;
  calendar: DateValueType;
};


const ExceptionCapacityModal = ({ show, setShow, capacities, setCapacities, handleCalendarChange, calendar }: ExceptionCapacityModalTypes) => {
  const handleCapacityException = (e: React.FormEvent<HTMLFormElement>) => {
    e.preventDefault();
    const elements = e.currentTarget.elements as HTMLFormControlsCollection;

    const newCapacityException = {
      csrf: (elements.namedItem("csrf") as HTMLInputElement)?.value,
      date: calendar?.startDate,
      capacity: (elements.namedItem("capacity") as HTMLInputElement)?.value,
    }

    const isCapacityExceptionExist = capacities.some(capacity => {
      return capacity.date === newCapacityException.date;
    });

    if (newCapacityException.date && newCapacityException.date <= new Date().toISOString().split('T')[0]) return toast.warning('Erre a napra már nem adhat hozzá kivételt!');
    if (isCapacityExceptionExist) return toast.warning('Erre a napra már adott hozzá kivételt!');

    fetchAuthentication.post(`/api/capacity/new`, newCapacityException).then(res => {
      const { exception } = res.data;
      setCapacities(prev => [...prev, exception]);
      toast.success('Soron következő kapacitás sikeresen frissítve!');
      setShow(false);
    }).catch((err) => {
      console.error(err);
      toast.error('Általános szerver hiba!');
    })

  }


  return (
    <Modal show={show} setShow={setShow} title='Kivétel hozzáadása'>
      <form onSubmit={handleCapacityException}>
        <CSFR dependency={[]} />
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
  )
}

export default ExceptionCapacityModal
