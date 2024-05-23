import React from 'react'
import TimelineItem from './TimeLineItem'
import { ReservationsTypes } from '../../../types/ReservationsTypes'

interface TimelineTypes {
  reservations: ReservationsTypes[]
}

const TimeLine = ({ reservations }: TimelineTypes) => {

  console.log(reservations);

  return (
    <ol className="relative border-s border-gray-200 dark:border-gray-700">
      <TimelineItem/>
    </ol>
  )
}

export default TimeLine
