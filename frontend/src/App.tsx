import './App.css'
import { Route, RouterProvider, createBrowserRouter, createRoutesFromElements } from 'react-router-dom';
import MainLayout from './layout/MainLayout';
import Welcome from './pages/Welcome';



const router = createBrowserRouter(
  createRoutesFromElements(<Route element={<MainLayout />} >
    <Route path='/' element={<Welcome />} />
  </Route>)
);

function App() {
  return (
    <RouterProvider router={router} />
  )
}

export default App