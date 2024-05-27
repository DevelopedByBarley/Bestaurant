import axios from 'axios';
import { useEffect, useState } from 'react'


type CSFRType = {
  dependency: any
}

const CSFR = ({ dependency = null }: CSFRType) => {
  const [token, setToken] = useState('')
  useEffect(() => {
    axios.get('/token').then(res => setToken(res.headers['x-csrf-token']))
    console.log('Hello csfr!');
  }, [dependency]);
  return <input type="hidden" name="csrf" value={token} />

}

export default CSFR
