import React, { useState } from 'react';
import {
  Card,
  CardBody,
  CardHeader,
  CardFooter,
  Avatar,
  Typography,
  Tabs,
  TabsHeader,
  Tab,
  Switch,
  Tooltip,
  Button,
} from "@material-tailwind/react";
import {
  HomeIcon,
  ChatBubbleLeftEllipsisIcon,
  Cog6ToothIcon,
  PencilIcon,
} from "@heroicons/react/24/solid";
import { Link } from "react-router-dom";
import { ProfileInfoCard, MessageCard } from "@/widgets/cards";
import { platformSettingsData, conversationsData, projectsData } from "@/data";
import { postNewJob } from '@/reports';

export function Profile() {
  // Estado para los campos del formulario
  const [formData, setFormData] = useState({
    job_id: generateRandomNumbers(),
    title: '',
    city: '',
    country: '',
    category: '',
    type: '',
    experience: '',
    description: '',
    responsibilities: '',
    requirements: '',
    company: 'CM404477675',
    deadline: '',  // Fecha en formato YYYY-MM-DD
  });

  // Función para generar un número aleatorio de 10 dígitos
  function generateRandomNumbers() {
    let randomNumber = Math.floor(Math.random() * 10000000000); // 10 dígitos
    while (randomNumber.toString().length < 10) {
      randomNumber = Math.floor(Math.random() * 10000000000); // 10 dígitos
    }
    console.log("Número generado:", randomNumber);
    return randomNumber;
  }

  // Maneja los cambios en los inputs
  const handleChange = (e) => {
    const { name, value } = e.target;
    setFormData((prevData) => ({
      ...prevData,
      [name]: value,
    }));
  };

  // Maneja el envío del formulario
  const handleSubmit = (event) => {
    event.preventDefault();

    // Convertir la fecha al formato DD/MM/YYYY antes de enviarla
    const formattedDeadline = formatDateForSubmit(formData.deadline);

    // Crear un objeto con los datos formateados
    const dataToSend = { ...formData, deadline: formattedDeadline };

    // Llamar a la función para publicar el empleo con los datos del formulario
    postNewJob(dataToSend);
  };

  // Función para convertir la fecha en formato DD/MM/YYYY
  const formatDateForSubmit = (date) => {
    if (!date) return '';  // Si no hay fecha, retornamos un string vacío
    const [year, month, day] = date.split('-');
    return `${day}/${month}/${year}`;  // Convertimos a DD/MM/YYYY
  };

  // Maneja los cambios en la fecha
  const handleDateChange = (e) => {
    const rawDate = e.target.value;
    if (/^\d{4}-\d{2}-\d{2}$/.test(rawDate)) {
      setFormData((prevData) => ({
        ...prevData,
        deadline: rawDate,  // Guardamos la fecha en formato YYYY-MM-DD para el input de tipo "date"
      }));
    }
  };

  // Función para mostrar la fecha en formato DD/MM/YYYY
  const formatDateForView = (date) => {
    const [year, month, day] = date.split('-');
    return `${day}/${month}/${year}`;  // Convertimos a DD/MM/YYYY
  };

  return (
    <>
      <div className="relative mt-8 h-72 w-full overflow-hidden rounded-xl bg-[url('/img/Fondo.png')] bg-cover	bg-center">
        <div className="absolute inset-0 h-full w-full bg-gray-900/75" />
      </div>
      <Card className="mx-3 -mt-16 mb-6 lg:mx-4 border border-blue-gray-100">
        <CardBody className="p-4">
          <div className="mb-10 flex items-center justify-between flex-wrap gap-6">
            <div className="flex items-center gap-6">
              <Avatar
                src="/img/FullColorInt.png"

                alt="bruce-mars"
                size="xl"
                variant="rounded"
                className="rounded-lg shadow-lg shadow-blue-gray-500/40"
              />
              <div>
                <Typography variant="h5" color="blue-gray" className="mb-1">
                  Insituto Tecnologico Superior Nelson Torres
                </Typography>
                <Typography
                  variant="small"
                  className="font-normal text-blue-gray-600"
                >
                  Bolsa de Empleo Int Creado por Omar Sani
                </Typography>
              </div>
            </div>
            <div className="w-96">
              <Tabs value="app">
                <TabsHeader>
                  <Tab value="app">
                    <HomeIcon className="-mt-1 mr-2 inline-block h-5 w-5" />
                    App
                  </Tab>
                  <Tab value="message">
                    <ChatBubbleLeftEllipsisIcon className="-mt-0.5 mr-2 inline-block h-5 w-5" />
                    Message
                  </Tab>
                  <Tab value="settings">
                    <Cog6ToothIcon className="-mt-1 mr-2 inline-block h-5 w-5" />
                    Settings
                  </Tab>
                </TabsHeader>
              </Tabs>
            </div>
          </div>
          <div className='grid lg:grid-cols-2 gap-4'>
          <form onSubmit={handleSubmit} className="grid-cols-1 mb-12 grid gap-12 px-4 ">
            <div>
              <Typography variant="h6" color="black" className="mb-3">
                Publicar Oferta de Empleo
              </Typography>

              {/* Campos del formulario */}
              <div className="mb-3">
                <p>Titulo del Empleo</p>
                <input
                  type="text"
                  name="title"
                  value={formData.title}
                  onChange={handleChange}
                  placeholder="Título de la oferta"
                  className="w-full p-2 border-2 border-black focus:border-red-500 focus:scale-105 transition-transform rounded-lg"
                />
              </div>

              <div className="grid lg:grid-cols-2 gap-4">
                <div className="mb-3">
                  <p>Ciudad</p>
                  <input
                    type="text"
                    name="city"
                    value={formData.city}
                    onChange={handleChange}
                    placeholder="Descripción del puesto"
                    className="w-full p-2 border-2 border-black focus:border-red-500 focus:scale-105 transition-transform rounded-lg"
                  />
                </div>
                <div className="mb-3">
                  <p>Pais</p>
                  <input
                    type="text"
                    name="country"
                    value={formData.country}
                    onChange={handleChange}
                    placeholder="Ubicación"
                    className="w-full p-2 border-2 border-black focus:border-red-500 focus:scale-105 transition-transform rounded-lg"
                  />
                </div>
              </div>

              {/* Resto de campos del formulario */}
              <div className="grid lg:grid-cols-2 gap-4">
                <div className="mb-3">
                  <p>Categoria</p>
                  <input
                    type="text"
                    name="category"
                    value={formData.category}
                    onChange={handleChange}
                    placeholder="Requisitos"
                    className="w-full p-2 border-2 border-black focus:border-red-500 focus:scale-105 transition-transform rounded-lg"
                  />
                </div>
                <div className="mb-3">
                  <p>Fecha de cierre</p>
                  <input
                    type="date"
                    name="deadline"
                    value={formData.deadline}  // El valor se mantiene en formato YYYY-MM-DD
                    onChange={handleDateChange}
                    className="w-full p-2 border-2 border-black focus:border-red-500 focus:scale-105 transition-transform rounded-lg"
                  />
                </div>
              </div>

              {/* Más campos */}
              <div className="grid lg:grid-cols-2 gap-4">
                <div className="mb-3">
                  <p>Rubro</p>
                  <select
                    name="type"
                    value={formData.type}
                    onChange={handleChange}
                    className="w-full p-2 border-2 border-black focus:border-red-500 focus:scale-105 transition-transform rounded-lg"
                  >
                    <option value="">Selecciona un tipo de contrato</option>
                    <option value="Full-time">Full-time</option>
                    <option value="Part-time">Part-time</option>
                    <option value="Freelance">Freelance</option>
                  </select>
                </div>

                <div className="mb-3">
                  <p>Experiencia</p>
                  <input
                    type="text"
                    name="experience"
                    value={formData.experience}
                    onChange={handleChange}
                    placeholder="Salario"
                    className="w-full p-2 border-2 border-black focus:border-red-500 focus:scale-105 transition-transform rounded-lg"
                  />
                </div>
              </div>

              {/* Descripción de la vacante y otros */}
              <div className="mb-3">
                <p>Descripcion de la vacante</p>
                <textarea
                  name="description"
                  value={formData.description}
                  onChange={handleChange}
                  placeholder="Beneficios"
                  className="w-full p-2 border-2 border-black focus:border-red-500 focus:scale-105 transition-transform rounded-lg"
                  rows="4"
                />
              </div>

              <div className="mb-3">
                <p>Responsabilidades</p>
                <textarea
                  name="responsibilities"
                  value={formData.responsibilities}
                  onChange={handleChange}
                  placeholder="Fecha límite de postulación"
                  className="w-full p-2 border-2 border-black focus:border-red-500 focus:scale-105 transition-transform rounded-lg"
                  rows="4"
                />
              </div>

              <div className="mb-3">
                <p>Requerimientos</p>
                <textarea
                  name="requirements"
                  value={formData.requirements}
                  onChange={handleChange}
                  placeholder="Contacto"
                  className="w-full p-2 border-2 border-black focus:border-red-500 focus:scale-105 transition-transform rounded-lg"
                  rows="4"
                />
              </div>

              {/* Botón para publicar el empleo */}
              <div className="mb-3">
                <button
                  type="submit"
                  className="w-full p-2 bg-red-800 text-white rounded hover:bg-black">
                  Publicar Empleo
                </button>
              </div>
            </div>
          </form>

          <ProfileInfoCard
            title="Profile Information"
            description="Esta Seccion esta siendo creda para crear ofertas de empleo unicamente desde la cuenta original y oficial de la Bolsa de empelo del Instituto Nelson Torres."
            details={{
              "Cuenta": "Instituto Nelson Torres",
              mobile: "0963285407",
              email: "bolsa.empleo@intsuperior.edu.ec",
              location: "ECU",
              social: (
                <div className="flex items-center gap-4">
                  <i className="fa-brands fa-facebook text-blue-700" />
                  <i className="fa-brands fa-twitter text-blue-400" />
                  <i className="fa-brands fa-instagram text-purple-500" />
                </div>
              ),
            }}
            action={
              <Tooltip content="Edit Profile">
                <PencilIcon className="h-4 w-4 cursor-pointer text-blue-gray-500" />
              </Tooltip>
            }
          />
</div>
          <div className="px-4 pb-4">
            <Typography variant="h6" color="blue-gray" className="mb-2">
              Projects
            </Typography>
            <Typography
              variant="small"
              className="font-normal text-blue-gray-500"
            >
              Architects design houses
            </Typography>
            <div className="mt-6 grid grid-cols-1 gap-12 md:grid-cols-2 xl:grid-cols-4">
              {projectsData.map(
                ({ img, title, description, tag, route, members }) => (
                  <Card key={title} color="transparent" shadow={false}>
                    <CardHeader
                      floated={false}
                      color="gray"
                      className="mx-0 mt-0 mb-4 h-64 xl:h-40"
                    >
                      <img
                        src={img}
                        alt={title}
                        className="h-full w-full object-cover"
                      />
                    </CardHeader>
                    <CardBody className="py-0 px-1">
                      <Typography
                        variant="small"
                        className="font-normal text-blue-gray-500"
                      >
                        {tag}
                      </Typography>
                      <Typography
                        variant="h5"
                        color="blue-gray"
                        className="mt-1 mb-2"
                      >
                        {title}
                      </Typography>
                      <Typography
                        variant="small"
                        className="font-normal text-blue-gray-500"
                      >
                        {description}
                      </Typography>
                    </CardBody>
                    <CardFooter className="mt-6 flex items-center justify-between py-0 px-1">
                      <Link to={route}>
                        <Button variant="outlined" size="sm">
                          view project
                        </Button>
                      </Link>
                      <div>
                        {members.map(({ img, name }, key) => (
                          <Tooltip key={name} content={name}>
                            <Avatar
                              src={img}
                              alt={name}
                              size="xs"
                              variant="circular"
                              className={`cursor-pointer border-2 border-white ${key === 0 ? "" : "-ml-2.5"
                                }`}
                            />
                          </Tooltip>
                        ))}
                      </div>
                    </CardFooter>
                  </Card>
                )
              )}
            </div>
          </div>
        </CardBody>
      </Card>
    </>
  );
}

export default Profile;
