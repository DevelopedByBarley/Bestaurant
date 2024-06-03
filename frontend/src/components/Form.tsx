
import React, { Dispatch, SetStateAction, useContext, useState } from 'react'
import CSFR from "../components/CSFR";
import { toast } from 'react-toastify';
import { useLocation, useNavigate } from 'react-router-dom';
import { DateValueType } from 'react-tailwindcss-datepicker';
import { ModalContext } from '../context/ModalContext';
import { minLength, required, trimTwo, validateEmail, validatePhoneNumber } from '../helpers/Validations';
import { fetchAuthentication } from '../services/AuthService';

type FormTypes = {
  calendar: DateValueType;
  selectedReservationDateRange: {
    from: string,
    to: string
  };
  numOfGuests: number | null
  interval: string | null
  setPage: Dispatch<SetStateAction<number>>;
}

const Form = ({ calendar, selectedReservationDateRange, numOfGuests, interval, setPage }: FormTypes) => {
  const location = useLocation();
  const navigate = useNavigate();
  const { setModal } = useContext(ModalContext)
  const [nameErrors, setNameErrors] = useState<string[]>([]);
  const [mailErrors, setMailErrors] = useState<string[]>([]);
  const [phoneErrors, setPhoneErrors] = useState<string[]>([]);

  console.log(location.pathname)


  const sendReservation = (e: React.FormEvent<HTMLFormElement>) => {
    e.preventDefault();


    const elements = e.currentTarget.elements as HTMLFormControlsCollection;

    const newReservation = {

      date: calendar?.startDate,
      start: selectedReservationDateRange?.from,
      end: selectedReservationDateRange?.to,
      numOfGuests: numOfGuests,
      interval: interval,
      csrf: (elements.namedItem("csrf") as HTMLInputElement)?.value,
      name: (elements.namedItem("name") as HTMLInputElement)?.value,
      phone: (elements.namedItem("phone") as HTMLInputElement)?.value,
      email: (elements.namedItem("email") as HTMLInputElement)?.value,
      request: (elements.namedItem("request") as HTMLInputElement)?.value,
    }
    fetchAuthentication.post('/api/reservation/new', newReservation).then(() => {
      toast.success('Időpont foglalási kérelme megtörtént. A foglalás részleteiről e-mailt küldünk miután az étterem elfogadta és véglegesítette.')
      setModal(false);
      return navigate(location.pathname === '/admin/reservations' ? '/admin/reservations' : '/');

    }).catch((err) => {
      console.error(err);
      toast.error('Általános szerver hiba. Kérjük próbálkozzon később');
    })
  }





  return (
    <form onSubmit={sendReservation}>
      <div className="w-full xl:grid grid-cols-2 gap-8 p-5">
        <div className="relative z-0 mb-5 group">
          <input type="text" name="name" id="floating_first_name" className="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer" placeholder=" " onChange={(e) => {
            console.log(nameErrors)
            required(e, setNameErrors)
            minLength(e, 3, setNameErrors)
            trimTwo(e, setNameErrors)
          }} required />
          <label htmlFor="floating_first_name" className="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-8 scale-75 top-3 -z-10 origin-[0] peer-focus:start-0 rtl:peer-focus:translate-x-1/4 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-8">Teljes név</label>
          {nameErrors.map(err => (
            <p className="text-red-500">{err}</p>
          ))}
        </div>
        <div className="relative z-0 mb-5 group">
          <input type="text" name="phone" id="floating_last_name" className="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer" placeholder=" " required
            onChange={(e) => {
              required(e, setPhoneErrors)
              minLength(e, 10, setPhoneErrors)
              validatePhoneNumber(e, setPhoneErrors)
            }} />
          <label htmlFor="ast_name" className="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-8 scale-75 top-3 -z-10 origin-[0] peer-focus:start-0 rtl:peer-focus:translate-x-1/4 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-8">Telefonszám</label>
          {phoneErrors.map(err => (
            <p className="text-red-500">{err}</p>
          ))}
        </div>
        <div className="relative z-0 mb-5 group col-span-2">
          <input type="email" name="email" id="floating_last_name" className="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer" placeholder=" " required onChange={(e) => {
            required(e, setMailErrors)
            validateEmail(e, setMailErrors)
          }} />
          <label htmlFor="floating_last_name" className="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-8 scale-75 top-3 -z-10 origin-[0] peer-focus:start-0 rtl:peer-focus:translate-x-1/4 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-8">
            E-mail
          </label>
          {mailErrors.map(err => (
            <p className="text-red-500">{err}</p>
          ))}
        </div>
        <div className="relative z-0 mb-5 group col-span-2">
          <input type="text" name="request" id="floating_last_name" className="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer" placeholder=" " required />
          <label htmlFor="floating_last_name" className="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-8 scale-75 top-3 -z-10 origin-[0] peer-focus:start-0 rtl:peer-focus:translate-x-1/4 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-8">Különleges kérések</label>
        </div>
      </div>
      <CSFR dependency={''} />


      <div className="p-5">
        <button onClick={() => {
          setPage(prev => prev - 1);
        }} type="button" className="text-white bg-gray-800 hover:bg-gray-900 focus:outline-none focus:ring-4 focus:ring-gray-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-gray-800 dark:hover:bg-gray-700 dark:focus:ring-gray-700 dark:border-gray-700">Vissza</button>
        <button type="submit" className="text-gray-900 bg-white border border-gray-300 focus:outline-none hover:bg-gray-100 focus:ring-4 focus:ring-gray-100 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-gray-800 dark:text-white dark:border-gray-600 dark:hover:bg-gray-700 dark:hover:border-gray-600 dark:focus:ring-gray-700">Foglalás</button>
      </div>

    </form>
  )
}

export default Form
