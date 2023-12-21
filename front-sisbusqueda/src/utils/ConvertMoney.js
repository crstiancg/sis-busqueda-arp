export function formatNumberToSoles(number) {
  // Configuración regional para soles peruanos
  const locale = 'es-PE';
  const currency = 'PEN';

  // Formatear el número usando Intl.NumberFormat
  const formatter = new Intl.NumberFormat(locale, {
    style: 'currency',
    currency: currency,
    minimumFractionDigits: 2,
  });
  return formatter.format(number);
}
