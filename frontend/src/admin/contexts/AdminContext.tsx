import  { Dispatch, SetStateAction, createContext, useContext, useEffect, useState, ReactNode } from 'react';

type AdminContextTypes = {
  isLoggedIn: boolean;
  setLoggedIn: Dispatch<SetStateAction<boolean>>;
};

const AdminContext = createContext<AdminContextTypes | undefined>(undefined);

export const useAdminContext = () => {
  const context = useContext(AdminContext);
  if (!context) {
    throw new Error("useAdminContext must be used within an AdminProvider");
  }
  return context;
};

type AdminProviderProps = {
  children: ReactNode;
};

const AdminProvider = ({ children }: AdminProviderProps) => {
  const [isLoggedIn, setLoggedIn] = useState(false);

  useEffect(() => {
    if (localStorage.getItem('accessToken')) setLoggedIn(true);
  }, []);

  return (
    <AdminContext.Provider value={{ isLoggedIn, setLoggedIn }}>
      {children}
    </AdminContext.Provider>
  );
};

export default AdminProvider;
