import React, { createContext, Dispatch, SetStateAction, useState } from "react";

type Modal = boolean;

type ModalContextType = {
  modal: Modal,
  setModal: Dispatch<SetStateAction<Modal>>
};

type ModalContextProviderProps = {
  children: React.ReactNode
};

export const ModalContext = createContext<ModalContextType>({
  modal: false,
  setModal: () => { }
});

export const ModalContextProvider = ({ children }: ModalContextProviderProps) => {
  const [modal, setModal] = useState<Modal>(false);

  return (
    <ModalContext.Provider value={{ modal, setModal }}>
      {children}
    </ModalContext.Provider >
  );
};