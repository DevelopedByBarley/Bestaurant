import React, { useState } from 'react';

export function Test() {
    const [guests, setGuests] = useState([
        { name: 'Kovács János', start: '10:00', end: '12:00', isAccepted: false },
        { name: 'Nagy Anna', start: '11:00', end: '12:00', isAccepted: true },
        { name: 'Szabó Péter', start: '14:00', end: '17:00', isAccepted: true },
        { name: 'Szabó Péter', start: '14:00', end: '17:00', isAccepted: false },
        { name: 'Szabó Péter', start: '14:00', end: '17:00', isAccepted: true },
        { name: 'Szabó Péter', start: '18:00', end: '20:00', isAccepted: true },
        { name: 'Szabó Péter', start: '13:00', end: '17:00', isAccepted: true },
    ]);


    const [times, setTimes] = useState([
        { start: '10:00', end: '11:00' },
        { start: '11:00', end: '12:00' },
        { start: '12:00', end: '13:00' },
        { start: '13:00', end: '14:00' },
        { start: '14:00', end: '15:00' },
        { start: '15:00', end: '16:00' },
        { start: '16:00', end: '17:00', left: '600%' },
        { start: '17:00', end: '18:00', left: '700%' },
        { start: '18:00', end: '19:00', left: '800%' },
        { start: '19:00', end: '20:00', left: '900%' },
        { start: '20:00', end: '21:00', left: '1000%' },
    ]);

    return (
        <div className="relative overflow-x-auto shadow-md sm:rounded-lg">
            <table className="w-full text-sm text-left border border-collapse border-gray-900">
                <thead className="text-xs uppercase border-b border-gray-900" >
                    <tr >
                        {times.map((time, index) => (
                            <th

                                key={index}
                                scope="col"
                                className="py-3 border border-gray-900"
                            >
                                {time.start}
                            </th>
                        ))}
                    </tr>
                </thead>
                <tbody>
                    {guests.map((guest, guestIndex) => {
                        const startHour = Number(guest.start.split(':')[0]);
                        const endHour = Number(guest.end.split(':')[0]);


                        const guestWidth = (endHour - startHour) * 100 + '%';

                        const time = times.find(time => guest.start === time.start);
                        const leftWidth = time?.left || '0%';
                        return (
                            <tr key={guestIndex} className="relative border border-spacing-0 border-gray-900 p-0 m-0">
                                {times.map((time, index) => (
                                    <td key={index} className="relative py-5  border border-spacing-0 border-gray-900  m-0">
                                        {index === 0 && (
                                            <div
                                                className={`cursor-pointer absolute top-0  z-50 ${guest.isAccepted ? 'bg-green-400 hover:bg-green-500' : 'bg-red-400 hover:bg-red-500'} min-h-10 text-center text-white rounded-sm cursor-pointer`}
                                                style={{ width: guestWidth }}
                                            >{guest.name}</div>
                                        )}
                                    </td>
                                ))}
                            </tr>
                        );
                    })}
                </tbody>
            </table>
        </div>
    );
}
