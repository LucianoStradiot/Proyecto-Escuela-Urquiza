import React, { useEffect } from 'react';
import { Route, Routes, useLocation } from 'react-router-dom';
import Home from '../Components/Home';
import Carreras from '../Views/LandingView/Carreras';
import Inscripciones from '../Views/LandingView/Inscripciones';
import SuperAdmin from '../Views/SuperAdminView/SuperAdmin';
import AlumnoProfile from '../Views/AlumnoView/Profile';
import InscripcionesAlumno from '../Views/AlumnoView/InscripcionesAlumno';
import Materias from '../Views/AlumnoView/Materias';
import Error404 from '../Views/Error404';
import LandingView from '../Views/LandingView';
import AlumnoView from '../Views/AlumnoView';
import SuperAdminView from '../Views/SuperAdminView';
import SignUp from '../Views/LandingView/SignUp';
import RecoverPassword from '../Views/LandingView/Login/RecoverPassword';
import ResetPassword from '../Views/LandingView/Login/ResetPassword';
import Login from '../Views/LandingView/Login';

const RoutesLanding = () => {
  const { pathname } = useLocation();

  useEffect(() => {
    window.scrollTo(0, 0);
  }, [pathname]);

  return (
    <Routes>
      <Route path="/" element={<LandingView />}>
        <Route index element={<Home />} />
        <Route path="/signup" element={<SignUp />} />
        <Route path="/login" element={<Login />} />
        <Route path="/recover-password" element={<RecoverPassword />} />
        <Route path="/reset-password/:token" element={<ResetPassword />} />
        <Route path="/carreras" element={<Carreras />} />
        <Route path="/inscripciones" element={<Inscripciones />} />
      </Route>

      <Route path="/alumno" element={<AlumnoView />}>
        <Route index element={<Home />} />
        <Route path="profile/:id" element={<AlumnoProfile />} />
        <Route path="materias" element={<Materias />} />
        <Route path="inscripciones" element={<InscripcionesAlumno />} />
      </Route>

      <Route path="/super-admin" element={<SuperAdminView />}>
        <Route index element={<Home />} />
        <Route path="administracion" element={<SuperAdmin />} />
        <Route path="carreras" element={<Carreras />} />
        <Route path="inscripciones" element={<Inscripciones />} />
      </Route>

      <Route path="*" element={<Error404 />} />
    </Routes>
  );
};

export default RoutesLanding;
