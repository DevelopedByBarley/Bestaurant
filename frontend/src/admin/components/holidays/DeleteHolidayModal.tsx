import React from 'react'
import Modal from '../../../components/Modal'
import axios from 'axios';
import { HolidayType } from '../../pages/Holidays';

type HolidayModalType = {
    show: boolean;
    setShow: (show: boolean) => void;
    holidays: HolidayType[],
    setHolidays: (holidays: HolidayType[]) => void;
    currentHoliday: any
}


export const DeleteHolidayModal = ({ show, setShow, holidays, setHolidays, currentHoliday }: HolidayModalType) => {

    const deleteHoliday = (e: React.FormEvent<HTMLFormElement>) => {
        e.preventDefault();

        axios.post(`/api/holidays/delete/${currentHoliday.id}`).then(res => {
            const { status } = res.data;
            if (status) {
                const next = holidays.filter(holiday => holiday.id !== currentHoliday.id)
                setHolidays(next);
            }

        }).catch(err => console.error(err))
            .finally(() => {
                setShow(false);
            })
    }

    return (
        <Modal show={show} setShow={setShow} title='Kivétel törlése'>
            <form onSubmit={deleteHoliday}>
                <h1 className="text-2xl font-extrabold">Biztosan törli a következő kivételt?</h1>
                <div className="mt-5">
                    <h1 className="text-xl">{currentHoliday.date}</h1>
                    <h1 className="text-xl">{Number(currentHoliday.isHoliday) === 1 ? 'Ünnepnap' : currentHoliday.open + " - " + currentHoliday.close}</h1>
                </div>
                <div className="mt-5 space-x-5 flex">
                    <button type="submit" className={`borderbtn-green flex items-center justify-center`}>
                        Elfogad
                    </button>
                    <button
                        type='button'
                        onClick={() => setShow(false)}
                        className="btn-dark"
                    >Bezár</button>
                </div>
            </form>
        </Modal>
    )
}
