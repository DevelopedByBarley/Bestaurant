import './App.css'
import { Route, RouterProvider, createBrowserRouter, createRoutesFromElements } from 'react-router-dom';
import MainLayout from './layout/MainLayout';
import Main from './pages/Main';
import Error from './pages/Error';
import { ModalContextProvider } from './context/ModalContext';
import Login from './admin/pages/Login';
import Reservations from './admin/pages/Reservations';
import AdminLayout from './layout/AdminLayout';

const router = createBrowserRouter(
  createRoutesFromElements(
    <>
      <Route element={<MainLayout />} >
        <Route path='/' element={<Main />} />
        <Route path="*" element={<Error />} />
      </Route>
      <Route element={< AdminLayout />} >
        <Route path='/admin'>
          <Route path='' element={<Login />} />
          <Route path='reservations' element={<Reservations />} />
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
