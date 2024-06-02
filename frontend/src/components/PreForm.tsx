import axios from 'axios';
import React, { ChangeEvent, Dispatch, SetStateAction, useEffect, useState } from 'react';
import Datepicker, { DateValueType } from 'react-tailwindcss-datepicker';

type PreFormProps = {
  calendar: DateValueType;
  numOfGuests: number | null;
  setCalendar: Dispatch<SetStateAction<DateValueType>>;
  freeDates: DateType[] | null;
  setFreeDates: Dispatch<SetStateAction<DateType[] | null>>;
  setNumOfGuests: Dispatch<SetStateAction<number | null>>;
  setInterval: Dispatch<SetStateAction<string | null>>;
  selectedReservationDateRange: { from: string; to: string } | null;
  setSelectedReservationDateRange: Dispatch<SetStateAction<{ from: string; to: string } | null>>;
};

type DateType = {
  from: string;
  to: string;
  isReserved: boolean;
};

const PreForm: React.FC<PreFormProps> = ({
  calendar,
  numOfGuests,
  setCalendar,
  freeDates,
  setFreeDates,
  setNumOfGuests,
  setInterval,
  selectedReservationDateRange,
  setSelectedReservationDateRange,
}: PreFormProps) => {

  const [capacity, setCapacity] = useState(0);

  useEffect(() => {
    if (calendar?.startDate) {
      axios.get(`/api/capacity/${calendar.startDate}`).then(res => {
        const { data } = res.data;
        setCapacity(data);
        setNumOfGuests(prev => (prev === null || prev > data) ? data : prev);
      });
    }
  }, [calendar?.startDate, setNumOfGuests]);

  const handleCalendarChange = (dates: DateValueType | null) => {
    setCalendar(dates);
    if (freeDates) {
      setFreeDates(null);
    }
    if (selectedReservationDateRange) {
      setSelectedReservationDateRange(null);
    }
  };

  const handleIntervalChange = (event: ChangeEvent<HTMLSelectElement>) => {
    const newValue = event.target.value;
    setInterval(newValue);
    if (freeDates) {
      setFreeDates(null);
    }
    if (selectedReservationDateRange) {
      setSelectedReservationDateRange(null);
    }
  };



  const handleGuestsChange = (event: ChangeEvent<HTMLInputElement>) => {
    let newValue = parseInt(event.target.value);
    if (newValue > capacity) {
      newValue = capacity;
    }
    setNumOfGuests(newValue);
    setInterval(null);
    if (freeDates) {
      setFreeDates(null);
    }
    if (selectedReservationDateRange) {
      setSelectedReservationDateRange(null);
    }
  };

  return (
    <div className="grid md:grid-cols-3 gap-4 p-3 dark:bg-gray-900">
      <div>
        <label htmlFor="number-input" className="mt-5 block mb-2 text-sm font-medium text-gray-900 dark:text-white">
          Dátum kiválasztása
        </label>
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
        <label htmlFor="number-input" className="mt-5 block mb-2 text-sm font-medium text-gray-900 dark:text-white">
          Hányan érkeznek?
        </label>
        <input
          type="number"
          onChange={handleGuestsChange}
          min={1}
          max={capacity}
          required
          name="numOfGuests"
          id="number-input"
          value={numOfGuests ?? capacity}
          aria-describedby="helper-text-explanation"
          className="border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
        />
      </div>
      <div>
        <label htmlFor="countries" className="mt-5 block mb-2 text-sm font-medium text-gray-900 dark:text-white">
          Mennyi ideig?
        </label>
        <select
          required
          id="interval"
          onChange={handleIntervalChange}
          name="interval"
          className="border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
        >
          <option value={''}>
            Válassza ki hány órát szeretne maradni
          </option>
          {numOfGuests && numOfGuests <= 4 && <option value="1">1 óra</option>}
          {numOfGuests && numOfGuests < 7 && <option value="2">2 óra</option>}
          {numOfGuests && <option value="3">3 óra</option>}
        </select>
      </div>
    </div>
  );
};

export default PreForm;
