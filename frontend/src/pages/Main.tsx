import React, { useEffect } from 'react'
import logo from '../logo.svg';
import axios from 'axios';

const Main = () => {
  
  useEffect(() => {
    axios.get('/token').then(res => console.log(res.data));
  }, [])
  
  return (
    <div className="App">
      <header className="App-header">
        <img src={logo} className="App-logo" alt="logo" />
        <p>
          Edit <code>src/App.tsx</code> and save to reload.
        </p>
        <a
          className="App-link"
          href="https://reactjs.org"
          target="_blank"
          rel="noopener noreferrer"
        >
          Learn React
        </a>
      </header>
    </div>
  )
}

export default Main
