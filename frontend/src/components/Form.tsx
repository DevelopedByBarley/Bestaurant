import React from 'react'
import CSFR from "../components/CSFR";
import axios from 'axios';
import { toast } from 'react-toastify';
import { useNavigate } from 'react-router-dom';
import { DateValueType } from 'react-tailwindcss-datepicker';

type FormTypes = {
  calendar: DateValueType;
  selectedReservationDateRange: {
    from: string,
    to: string
  };
  numOfGuests: number | null
  interval: string | null
}

const Form = ({ calendar, selectedReservationDateRange, numOfGuests, interval }: FormTypes) => {
  const navigate = useNavigate();

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
      firstName: (elements.namedItem("firstName") as HTMLInputElement)?.value,
      lastName: (elements.namedItem("lastName") as HTMLInputElement)?.value,
      phone: (elements.namedItem("phone") as HTMLInputElement)?.value,
      request: (elements.namedItem("request") as HTMLInputElement)?.value,
    }



    axios.post('/reservation/new', newReservation).then((res) => {
      toast.success(res.data.message)
      return navigate('/reservation');
    }).catch((err) => {
      console.error(err);
      toast.error('Téma hozzáadása sikertelen!');
    })


  }

  return (
    <form onSubmit={sendReservation}>
      <div className="w-full grid grid-cols-2 gap-8 p-5">
        <div className="relative z-0 mb-5 group">
          <input type="text" name="firstName" id="floating_first_name" className="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer" placeholder=" " required />
          <label htmlFor="floating_first_name" className="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:start-0 rtl:peer-focus:translate-x-1/4 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">Vezetéknév</label>
        </div>
        <div className="relative z-0 mb-5 group">
          <input type="text" name="lastName" id="floating_last_name" className="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer" placeholder=" " required />
          <label htmlFor="floating_last_name" className="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:start-0 rtl:peer-focus:translate-x-1/4 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">Keresztnév</label>
        </div>
        <div className="relative z-0 mb-5 group">
          <input type="text" name="phone" id="floating_last_name" className="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer" placeholder=" " required />
          <label htmlFor="ast_name" className="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:start-0 rtl:peer-focus:translate-x-1/4 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">Telefonszám</label>
        </div>
        <div className="relative z-0 mb-5 group">
          <input type="email" name="email" id="floating_last_name" className="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer" placeholder=" " required />
          <label htmlFor="floating_last_name" className="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:start-0 rtl:peer-focus:translate-x-1/4 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">E-mail</label>
        </div>
        <div className="relative z-0 mb-5 group col-span-2">
          <input type="text" name="request" id="floating_last_name" className="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer" placeholder=" " required />
          <label htmlFor="floating_last_name" className="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:start-0 rtl:peer-focus:translate-x-1/4 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">Különleges kérések</label>
        </div>
      </div>
      <CSFR />




      <div className="p-5">
        <button type="submit" className="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Submit</button>
      </div>
    </form>
  )
}

export default Form
