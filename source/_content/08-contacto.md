---
# Área de contacto con formulario.
id: contacto
eyebrow: Hablemos
title: Agende su Cita
text: Cuéntenos brevemente su situación. Responderemos en menos de 24 horas hábiles para coordinar una primera conversación confidencial.
details:
  - icon: phone
    label: Teléfono
    value: "+52 55 1234 5678"
    href: "tel:+525512345678"
  - icon: mail
    label: Correo
    value: contacto@consensusfamily.com
    href: "mailto:contacto@consensusfamily.com"
  - icon: pin
    label: Oficina
    value: Ciudad de México, México
  - icon: clock
    label: Horario
    value: Lunes a viernes, 9:00 – 18:00 h
form:
  # Con `action: "#"` el envío se simula en el navegador (solo maqueta).
  # Para recibir mensajes con Netlify Forms: netlify: true y action: "/".
  action: "#"
  method: POST
  netlify: false
  netlify_name: contacto
  submit: Enviar Solicitud
  privacy: Al enviar acepta nuestro aviso de privacidad. Sus datos se tratan de forma estrictamente confidencial.
  fields:
    - name: nombre
      autocomplete: name
      label: Nombre completo
      type: text
      placeholder: Ej. María Fernanda Torres
      required: true
      width: half
    - name: empresa
      autocomplete: organization
      label: Empresa
      type: text
      placeholder: Nombre de su empresa familiar
      required: false
      width: half
    - name: email
      autocomplete: email
      label: Correo electrónico
      type: email
      placeholder: nombre@empresa.com
      required: true
      width: half
    - name: telefono
      autocomplete: tel
      label: Teléfono
      type: tel
      placeholder: "+52 55 0000 0000"
      required: false
      width: half
    - name: interes
      label: Tema de interés
      type: select
      required: true
      width: full
      options:
        - Protocolo Familiar
        - Plan de Sucesión
        - Gobierno Corporativo
        - Mediación de Conflictos
        - Otro
    - name: mensaje
      label: ¿Cómo podemos ayudarle?
      type: textarea
      placeholder: Describa brevemente la situación de su empresa familiar…
      required: true
      width: full
---
