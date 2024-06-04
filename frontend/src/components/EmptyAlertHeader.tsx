import React from 'react'

type EmptyAlertHeaderType = {
  message: string
}

const EmptyAlertHeader = ({ message }: EmptyAlertHeaderType) => {
  return (
    <div className="container my-10 text-4xl text-center col-span-3 font-extrabold">
      <h1>{message}</h1>
    </div>
  )
}

export default EmptyAlertHeader
