  import { Dispatch, SetStateAction } from "react";

  // Definiáljuk a Reservation típust
  export type ReservationsTypes = {
    id: number;
    name: string;
    date: string;
    start: string;
    end: string;
    numOfGuests: number;
    isAccepted: boolean;
    email: string;
    phone: string;
    request: string;
  }

  // Definiáljuk a fetch válaszának típusát
  export type FetchResponseTypes = {
    data: ReservationsTypes[];
    numOfPage: number;
  }

  export type ReservationsTableType = {
    reservations: ReservationsTypes[]
    setReservations: Dispatch<SetStateAction<ReservationsTypes[]>>
    sortConfig: {
      key: keyof ReservationsTypes | null;
      direction: "asc" | "desc" | null | '';
    }
    requestSort: (key: keyof ReservationsTypes) => void
  }