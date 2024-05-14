import { Dispatch, SetStateAction } from 'react';
import { DateType } from '../pages/Reservation';

type TimeListItemType = {
  index: number;
  date: DateType;
  from: string;
  to: string;
  setSelectedReservationDateRange: Dispatch<SetStateAction<{ from: string; to: string; } | null>>
}

const TimeListItem = ({ index, date, from, to, setSelectedReservationDateRange }: TimeListItemType) => {

  const handleDateChange = (from: string, to: string) => {
    setSelectedReservationDateRange ({ from, to });
  };
  return (
    <div>
      <li key={index}>
        <input
          type="radio"
          id={`date-${index}`}
          name="hosting"
          value="hosting-small"
          className="hidden peer"
          onClick={() => handleDateChange(from, to)}
          disabled={date.isReserved}
          required
        />
        <label
          htmlFor={`date-${index}`}
          className={`${date.isReserved ? 'bg-gray-300 hover:bg-gray-300' : ''} inline-flex items-center justify-between w-full p-5 text-gray-500 bg-white border border-gray-200 cursor-pointer dark:hover:text-gray-300 dark:border-gray-700 dark:peer-checked:text-blue-500 peer-checked:border-blue-600 peer-checked:text-blue-600 hover:text-gray-600 hover:bg-gray-100 dark:text-gray-400 dark:bg-gray-800 dark:hover:bg-gray-700`}
        >
          <div className="block w-full">
            <div className="*:text-lg font-semibold text-center">{date.isReserved ? <s>{from}</s> : <>{from}</>}</div>
          </div>
        </label>
      </li>
    </div>
  )
}

export default TimeListItem
