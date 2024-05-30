import './App.css'
import { Route, RouterProvider, createBrowserRouter, createRoutesFromElements } from 'react-router-dom';
import MainLayout from './layout/MainLayout';
import Main from './pages/Main';
import Error from './pages/Error';
import { ModalContextProvider } from './context/ModalContext';
import Login from './admin/pages/Login';
import Reservations from './admin/pages/Reservations';
import AdminLayout from './layout/AdminLayout';
import Capacities from './admin/pages/Capacities';
import AdminList from './admin/pages/AdminList';
import Opening from './admin/pages/Opening';
import Holidays from './admin/pages/Holidays';
import Menu from './admin/pages/Menu';

const router = createBrowserRouter(
  createRoutesFromElements(
    <>
      <Route path="*" element={<Error />} />
      <Route element={<MainLayout />} >
        <Route path='/' element={<Main />} />
      </Route>
      <Route element={< AdminLayout />} >
        <Route path='/admin'>
          <Route path='' element={<Login />} />
          <Route path='reservations' element={<Reservations />} />
          <Route path='capacities' element={<Capacities />} />
          <Route path='admin-list' element={<AdminList />} />
          <Route path='opening' element={<Opening />} />
          <Route path='holidays' element={<Holidays />} />
          <Route path='menu' element={<Menu />} />
        </Route>
      </Route>
    </>
  )
);

function App() {
  return (
    <ModalContextProvider>
      <RouterProvider router={router} />
    </ModalContextProvider>
  )
}

export default App;
