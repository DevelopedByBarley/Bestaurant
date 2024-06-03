import React from 'react'
import { CapacityTypes } from '../pages/Capacities';
import { IoFilterOutline } from 'react-icons/io5';
import { GoArrowDown, GoArrowUp } from 'react-icons/go';


type CapacitiesTableTypes = {
    capacities: CapacityTypes[]
    sortConfig: {
        key: keyof CapacityTypes | null;
        direction: "asc" | "desc" | null | '';
      }
      requestSort: (key: keyof CapacityTypes) => void
}

export const CapacitiesTable = ({ capacities , sortConfig, requestSort }: CapacitiesTableTypes) => {


    const renderArrowBySortDirection = () => {
        if (sortConfig.direction === 'desc') {
          return <GoArrowDown />;
        } else if (sortConfig.direction === 'asc') {
          return <GoArrowUp />;
        } else {
          return <IoFilterOutline />;
        }
      };

    return (
        <div>
            <table className="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400 mt-5">
                <thead className="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                    <tr>
                        <th scope="col-1" className="px-2 py-3 hover:cursor-pointer hover:text-cyan-400" onClick={() => requestSort('id')}>
                            <div className="flex items-center justify-center">
                                # <span className='text-lg mx-1'>{sortConfig.key === 'id' ? renderArrowBySortDirection() : <IoFilterOutline />}</span>
                            </div>
                        </th>
                        <th scope="col" className="px-6 py-3 hover:cursor-pointer hover:text-cyan-400"  onClick={() => requestSort('date')}>
                            <div className="flex items-center justify-center">
                                Dátum <span className='text-lg mx-1'>{sortConfig.key === 'date' ? renderArrowBySortDirection() : <IoFilterOutline />}</span>
                            </div>
                        </th>
                        <th scope="col" className="px-6 py-3 hover:cursor-pointer hover:text-cyan-400"  onClick={() => requestSort('capacity')}>
                            <div className="flex items-center justify-center">
                                Kapacitás <span className='text-lg mx-1'>{sortConfig.key === 'capacity' ? renderArrowBySortDirection() : <IoFilterOutline />}</span>
                            </div>
                        </th>
                        <th scope="col" className="px-16 py-3 hover:cursor-pointer hover:text-cyan-400"  onClick={() => requestSort('created_at')}>
                            <div className="flex items-center justify-center">
                                Létrehozva <span className='text-lg mx-1'>{sortConfig.key === 'created_at' ? renderArrowBySortDirection() : <IoFilterOutline />}</span>
                            </div>
                        </th>
                        <th scope="col" className="px-16 py-3 hover:cursor-pointer hover:text-cyan-400">
                            <div className="flex items-center justify-center">
                                Műveletek <span className='text-lg mx-1'></span>
                            </div>
                        </th>
                    </tr>
                </thead>
                <tbody>
                    {capacities.map((capacity) => (
                        <tr key={capacity.id} className={`bg-gray-100 hover:bg-gray-200 dark:bg-gray-600 dark:hover:bg-gray-500  transition text-center`}>
                            <th scope="row" className="px-6 py-4 text-gray-900 whitespace-nowrap dark:text-white font-extrabold">{capacity.id}</th>
                            <th scope="row" className="px-6 py-4 font-extrabold text-gray-900 whitespace-nowrap dark:text-white">{capacity.date}</th>
                            <th scope="row" className="px-6 py-4 font-extrabold text-gray-900 whitespace-nowrap dark:text-white">{capacity.capacity}</th>
                            <th scope="row" className="px-6 py-4 font-extrabold text-gray-900 whitespace-nowrap dark:text-white">{capacity.created_at}</th>
                            <th scope="row" className="px-6 py-4 font-extrabold text-gray-900 whitespace-nowrap dark:text-white">
                                <button className="btn-yellow">
                                    Frissítés
                                </button>
                                <button className="btn-red">
                                    Törlés
                                </button>
                            </th>

                        </tr>
                    ))}
                </tbody>
            </table >

        </div >
    );
}
