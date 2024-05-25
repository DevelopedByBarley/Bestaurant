import axios from "axios";
import { useCallback, useContext, useEffect, useState } from "react";
import { DateValueType } from "react-tailwindcss-datepicker";
import { toast } from "react-toastify";
import TimeListItem from "../components/TimeListItem";
//import FeedBack from "../components/FeedBack";
import Form from "../components/Form";
import PreForm from "../components/PreForm";
import { ModalContext } from "../context/ModalContext";


export type DateType = {
  from: string,
  to: string,
  isReserved: boolean
}



const Reservation = () => {
  const { setModal } = useContext(ModalContext);
  const [page, setPage] = useState(1);
  const [calendar, setCalendar] = useState<DateValueType>({   // SET CALENDAR
    startDate: new Date().toISOString().split('T')[0],
    endDate: new Date().toISOString().split('T')[0]
  });
  const [numOfGuests, setNumOfGuests] = useState<null | number>(1);
  const [interval, setInterval] = useState<null | string>(null);  // SET INTERVAL FOR USER FOY STAYING IN RESTAURANT
  const [freeDates, setFreeDates] = useState<DateType[] | null>(null);   // FREE DATES FOR PICK RESERVATION
  const [selectedReservationDateRange, setSelectedReservationDateRange] = useState<{ from: string, to: string } | null>(null);   // PICKED TIME FOR RESERVATION
  const checkIsFormFilledForFreeIntervals = useCallback(() => {
    if (interval && numOfGuests && calendar?.startDate) {
      const reservationData = {
        date: calendar.startDate,
        numOfGuests,
        interval
      };

      axios.post('/api/reservation', reservationData)
        .then(res => {
          const { data } = res.data;
          if (res.status === 500) {
            toast.info(res.data.message);
            return;
          }
          setFreeDates(data)
          setPage(prev => prev + 1);
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
    <div className={`mx-auto h-screen md:h-auto container fixed z-40 left-0 right-0 top-16 mt-1 bg-white dark:bg-gray-900 dark:text-white lg:w-3/5 ${page === 1 ? 'fade-top' : ''}`}>
      <div className="reservation-container">
        <div className="w-full flex items-start justify-between">
          <h1 className="text-3xl font-light mt-3 px-5  mb-5">Foglalás leadása</h1>
          <button onClick={() => setModal(false)} type="button" className="bg-white rounded-md p-2 inline-flex items-center justify-center text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-indigo-500 dark:bg-gray-900 dark:hover:bg-gray-800">
            <span className="sr-only">Close menu</span>
            <svg className="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
              <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
          </button>
        </div>
        {
          page === 1 && (
            <section>
              <PreForm
                calendar={calendar}
                numOfGuests={numOfGuests}
                setCalendar={setCalendar}
                freeDates={freeDates}
                setFreeDates={setFreeDates}
                setNumOfGuests={setNumOfGuests}
                setInterval={setInterval}
                selectedReservationDateRange={selectedReservationDateRange}
                setSelectedReservationDateRange={setSelectedReservationDateRange}
              />
            </section>
          )
        }



        {
          page === 2 && (
            <section className={`${page === 2 ? 'fade-top' : ''} max-h-[90vh] overflow-y-auto`}>
              <ul className="grid w-full gap-4 grid-cols-3 md:grid-cols-7 lg:grid-cols-5 xl:grid-cols-7 2xl:grid-cols-10 p-3 my-10">
                {freeDates ? freeDates.map((date, index) => {
                  return (
                    <TimeListItem index={index} setPage={setPage} date={date} from={date.from} to={date.to} selectedReservationDateRange={selectedReservationDateRange} setSelectedReservationDateRange={setSelectedReservationDateRange} />
                  );
                }) : ''}
              </ul>
              <div className="p-3 my-20 md:my-0">
                <button onClick={() => setPage(prev => prev - 1)} type="button" className="text-white bg-gray-800 hover:bg-gray-900 focus:outline-none focus:ring-4 focus:ring-gray-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-gray-800 dark:hover:bg-gray-700 dark:focus:ring-gray-700 dark:border-gray-700">Vissza</button>
                <button onClick={() => setPage(prev => prev + 1)} disabled={!selectedReservationDateRange} type="button" className={`${!selectedReservationDateRange ? 'bg-red-500 text-gray-300 hover:bg-red-500' : ''} text-gray-900 border border-gray-300 focus:outline-none hover:bg-gray-100 focus:ring-4 focus:ring-gray-100 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-gray-800 dark:text-white dark:border-gray-600 dark:hover:bg-gray-700 dark:hover:border-gray-600 dark:focus:ring-gray-700`}>Tovább</button>
              </div>
            </section>
          )
        }



        {/*   <section>
        {selectedReservationDateRange && (
          <FeedBack numOfGuests={numOfGuests} calendar={calendar} selectedReservationDateRange={selectedReservationDateRange} interval={interval} />
        )}
      </section> */}

        {
          page === 3 && (
            <section className={`mt-10 ${page === 3 ? 'fade-top' : ''}`}>
              {selectedReservationDateRange && (
                <Form calendar={calendar} selectedReservationDateRange={selectedReservationDateRange} numOfGuests={numOfGuests} interval={interval} setPage={setPage} />
              )}
            </section>
          )
        }
      </div >
    </div>
  )
}

export default Reservation;
