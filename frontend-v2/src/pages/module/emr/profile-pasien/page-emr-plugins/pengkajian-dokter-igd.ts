export function vitalSign(): any {
  return [
    {
      label: 'TD',
      model: 'tekananDarah',
      addon: 'mmHG',
    },
    {
      label: 'Nadi',
      model: 'nadi',
      addon: 'x/mnt',
    },
    {
      label: 'Pernafasan',
      model: 'pernapasan',
      addon: 'x/mnt',
    },
    {
      label: 'SPO2',
      model: 'SPO2',
      addon: '%',
    },
    {
      label: 'Skor Nyeri',
      model: 'skorNyeri',
      addon: '',
    },
    {
      label: 'Suhu',
      model: 'suhu',
      addon: '°C',
    },
  ]
}
