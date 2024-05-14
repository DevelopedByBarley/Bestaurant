import './App.css'
import { Route, RouterProvider, createBrowserRouter, createRoutesFromElements } from 'react-router-dom';
import MainLayout from './layout/MainLayout';
import Main from './pages/Main';
import Reservation from './pages/Reservation';
import Error from './pages/Error';



const router = createBrowserRouter(
  createRoutesFromElements(<Route element={<MainLayout />} >
    <Route path='/' element={<Main />} />
    <Route path='/reservation' element={<Reservation />} />
    <Route path="*" element={<Error />} />
  </Route>)
);

function App() {
  return (
    <RouterProvider router={router} />
  )
}

export default App