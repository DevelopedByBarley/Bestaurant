import { DateValueType } from 'react-tailwindcss-datepicker'

type FeedBackType = {
  numOfGuests: number | null;
  selectedReservationDateRange: {
    from: string,
    to: string
  };
  interval: string | null;
  calendar: DateValueType;
}

const FeedBack = ({numOfGuests, calendar, selectedReservationDateRange, interval}: FeedBackType) => {
  return (
    <div className="col-span-3 border p-5">
      <h1><b className="text-lg">Bevitt adatok:</b></h1>
      <p>Kiválasztott dátum: <b className="text-lg">{calendar?.startDate?.toString()}, {selectedReservationDateRange.from} - {selectedReservationDateRange.to}</b></p>
      <p>Hányan érkeznek: <b className="text-lg">{numOfGuests}</b></p>
      <p>Mennyi ideig: <b className="text-lg">{interval} óra</b></p>
    </div>
  )
}

export default FeedBack
