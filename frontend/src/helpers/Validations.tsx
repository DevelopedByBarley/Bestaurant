import React from 'react';

type SetErrorsFunction = React.Dispatch<React.SetStateAction<string[]>>;

export const required = (e: React.FormEvent<HTMLInputElement>, setErrors: SetErrorsFunction) => {
  const value = e.currentTarget.value;
  if (value.length < 1) {
    (e.currentTarget as HTMLInputElement).setCustomValidity('Not valid');
    setErrors(prev => {
      if (!prev.includes('A mező kitöltése kötelező!')) {
        return [...prev, 'A mező kitöltése kötelező!'];
      }
      return prev;
    });
  } else {
    (e.currentTarget as HTMLInputElement).setCustomValidity('');
    setErrors(prev => prev.filter(error => error !== 'A mező kitöltése kötelező!'));
  }
};

export const minLength = (e: React.FormEvent<HTMLInputElement>, length: number, setErrors: SetErrorsFunction) => {
  const value = e.currentTarget.value;
  if (value.length < length) {
    (e.currentTarget as HTMLInputElement).setCustomValidity('Not valid');
    setErrors(prev => {
      if (!prev.includes(`A mező hossza minimum ${length} kell lennie`)) {
        return [...prev, `A mező hossza minimum ${length} kell lennie`];
      }
      return prev;
    });
  } else {
    (e.currentTarget as HTMLInputElement).setCustomValidity('');
    setErrors(prev => prev.filter(error => error !== `A mező hossza minimum ${length} kell lennie`));
  }
};

export const validateEmail = (e: React.FormEvent<HTMLInputElement>, setErrors: SetErrorsFunction) => {
  const email = e.currentTarget.value;
  const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/; // Példa: egyszerű e-mail cím validációs regex
  if (!emailRegex.test(email)) {
    (e.currentTarget as HTMLInputElement).setCustomValidity('Érvénytelen e-mail cím');
    setErrors(prev => {
      if (!prev.includes('Érvénytelen e-mail cím')) {
        return [...prev, 'Érvénytelen e-mail cím'];
      }
      return prev;
    });
  } else {
    (e.currentTarget as HTMLInputElement).setCustomValidity('');
    setErrors(prev => prev.filter(error => error !== 'Érvénytelen e-mail cím'));
  }
};


export const trimTwo = (e: React.FormEvent<HTMLInputElement>, setErrors: SetErrorsFunction) => {
  const value = e.currentTarget.value.trim(); // Törli a felesleges szóközöket a kezdet és a végéről
  const words = value.split(/\s+/); // Szétválasztja a szavakat a szóközök mentén

  if (words.length !== 2) { // Ellenőrzi, hogy két szó van-e
    (e.currentTarget as HTMLInputElement).setCustomValidity('A mező értékének legalább 2 szót kell tartalmaznia');
    setErrors(prev => {
      if (!prev.includes('A mező értékének legalább 2 szót kell tartalmaznia')) {
        return [...prev, 'A mező értékének legalább 2 szót kell tartalmaznia'];
      }
      return prev;
    });
  } else {
    (e.currentTarget as HTMLInputElement).setCustomValidity('');
    setErrors(prev => prev.filter(error => error !== 'A mező értékének legalább 2 szót kell tartalmaznia'));
  }
};


export const validatePhoneNumber = (e: React.FormEvent<HTMLInputElement>, setErrors: SetErrorsFunction) => {
  const phoneNumber = e.currentTarget.value;
  const phoneRegex = /^\d+$/; // Csak számokat fogad el

  if (!phoneRegex.test(phoneNumber)) { // Ellenőrzi, hogy csak számokat tartalmaz-e
    (e.currentTarget as HTMLInputElement).setCustomValidity('Csak számokat adjon meg');
    setErrors(prev => {
      if (!prev.includes('Csak számokat adjon meg')) {
        return [...prev, 'Csak számokat adjon meg'];
      }
      return prev;
    });
  } else {
    (e.currentTarget as HTMLInputElement).setCustomValidity('');
    setErrors(prev => prev.filter(error => error !== 'Csak számokat adjon meg'));
  }
};
