import axios from "axios";
import { useCallback, useEffect, useState } from "react";
import Datepicker, { DateValueType } from "react-tailwindcss-datepicker";
import { toast } from "react-toastify";
import TimeListItem from "../components/TimeListItem";
import FeedBack from "../components/FeedBack";
import Form from "../components/Form";


export type DateType = {
  from: string,
  to: string,
  isReserved: boolean
}



const Reservation = () => {
  // SET CALENDAR
  const [calendar, setCalendar] = useState<DateValueType>({
    startDate: null,
    endDate: null
  });
  const [numOfGuests, setNumOfGuests] = useState<null | number>(null);
  // SET INTERVAL FOR USER FOY STAYING IN RESTAURANT
  const [interval, setInterval] = useState<null | string>(null);
  // FREE DATES FOR PICK RESERVATION
  const [freeDates, setFreeDates] = useState<DateType[] | null>(null);


  // PICKED TIME FOR RESERVATION
  const [selectedReservationDateRange, setSelectedReservationDateRange] = useState<{ from: string, to: string } | null>(null);


  const handleCalendarChange = (dates: DateValueType | null) => {
    setCalendar(dates);
    if (freeDates) {
      setFreeDates(null)
    }
    if (selectedReservationDateRange) {
      setSelectedReservationDateRange(null)
    }
  }

  const handleIntervalChange = (event: React.ChangeEvent<HTMLSelectElement>) => {
    const newValue = event.target.value;
    setInterval(newValue);
    if (freeDates) {
      setFreeDates(null)
    }
    if (selectedReservationDateRange) {
      setSelectedReservationDateRange(null)
    }
  };

  const handleGuestsChange = (event: React.ChangeEvent<HTMLInputElement>) => {
    const newValue = event.target.value;
    setNumOfGuests(parseInt(newValue));
    setInterval(null);
    if (freeDates) {
      setFreeDates(null)
    }
    if (selectedReservationDateRange) {
      setSelectedReservationDateRange(null)
    }
  };


  const checkIsFormFilledForFreeIntervals = useCallback(() => {
    if (interval && numOfGuests && calendar?.startDate) {
      const reservationData = {
        date: calendar.startDate,
        numOfGuests,
        interval
      };

      axios.post('/reservation', reservationData)
        .then(res => {
          const { data } = res.data;
          if (res.status === 500) {
            toast.info(res.data.message);
            return;
            
          }
          setFreeDates(data);
        })
        .catch(error => {
          console.error('Error while fetching free intervals:', error);
        });
    } else {
      console.log('not done');
    }
  }, [interval, numOfGuests, calendar]);

  useEffect(() => {
    checkIsFormFilledForFreeIntervals();
  }, [checkIsFormFilledForFreeIntervals]);


  return (
    <div className="mt-32 container lg:w-3/5 mx-auto ">
      <h1 className="text-3xl border p-5 my-8">Foglalás leadása</h1>
      <section>
        <div className="grid grid-cols-3 gap-4">
          <div>
            <label htmlFor="number-input" className="mt-5 block mb-2 text-sm font-medium text-gray-900 dark:text-white">Hányan érkeznek?</label>
            <div className="border rounded-lg">
              <Datepicker
                useRange={false}
                asSingle={true}
                value={calendar}
                onChange={handleCalendarChange}
                minDate={new Date()}
              />
            </div>
          </div>
          <div>
            <label htmlFor="number-input" className="mt-5 block mb-2 text-sm font-medium text-gray-900 dark:text-white">Hányan érkeznek?</label>
            <input type="number" onChange={handleGuestsChange} min={1} max={15} required name="numOfGuests" id="number-input" aria-describedby="helper-text-explanation" className=" border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" />
          </div>
          <div>
            <label htmlFor="countries" className="mt-5 block mb-2 text-sm font-medium text-gray-900 dark:text-white">Mennyi ideig?</label>
            <select required id="interval" onChange={handleIntervalChange} name="interval" className="border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
              <option selected value={''}>Válassza ki hány órát szeretne maradni</option>
              {numOfGuests && numOfGuests <= 4 && <option value="1">1 óra</option>}
              {numOfGuests && numOfGuests < 7 && <option value="2">2 óra</option>}
              {numOfGuests && <option value="3">3 óra</option>}
            </select>
          </div>

        </div>
      </section>



      <section>
        <ul className="grid w-full gap-4 grid-cols-3 md:grid-cols-7 lg:grid-cols-5 xl:grid-cols-7 2xl:grid-cols-10 p-3 my-10">
          {freeDates ? freeDates.map((date, index) => {
            return (
              <TimeListItem index={index} date={date} from={date.from} to={date.to} setSelectedReservationDateRange={setSelectedReservationDateRange} />
            );
          }) : ''}
        </ul>
      </section>



      <section>
        {selectedReservationDateRange && (
          <FeedBack numOfGuests={numOfGuests} calendar={calendar} selectedReservationDateRange={selectedReservationDateRange} interval={interval} />
        )}
      </section>

      <section className="mt-10  mb-52">
        {selectedReservationDateRange && (
          <Form calendar={calendar} selectedReservationDateRange={selectedReservationDateRange} numOfGuests={numOfGuests} interval={interval} />
        )}
      </section>
    </div >
  )
}

export default Reservation;
