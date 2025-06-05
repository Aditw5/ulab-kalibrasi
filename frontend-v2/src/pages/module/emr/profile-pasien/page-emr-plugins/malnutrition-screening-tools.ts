export function skriningGizi(): any {
  return [
    {
      label:
        ' 1. Apakah pasien mengalami penurunan berat badan yang tidak direncanakan?',
      children: [
        {
          label: 'a. Tidak',
          type: 'checkbox',
          model: 'tidakAdaTurunBeratBadan',
          text :"Tidak",
          value: 0,
        },
        {
          label: 'b. Tidak yakin / tidak tahu/ ya',
          type: 'checkbox',
          text :"Ya",
          model: 'tidakAdaTurunBeratBadan',
          value: 2,
        },
      ],
    },
    {
      label: ' 2. Bila ya, berapa kilogram penurunan berat badan tersebut',
      children: [
        {
          type: 'checkbox',
          label: '1 - 5 kg',
          model: 'turunBeratBadan',
          value: 1,
        },
        {
          type: 'checkbox',
          label: '6 - 10 kg',
          model: 'turunBeratBadan',
          value: 2,
        },
        {
          type: 'checkbox',
          label: '11 - 15 kg',
          model: 'turunBeratBadan',
          value: 3,
        },
        {
          type: 'checkbox',
          label: '> 15 kg',
          model: 'turunBeratBadan',
          value: 4,
        },
        {
          type: 'checkbox',
          label: 'Tidak pasti/tidak tahu',
          model: 'turunBeratBadan',
          value: 5,
        },
      ],
    },
    {
      label: ' 3. Penurunan nafsu makan / asupan makan ?',
      children: [
        {
          text :"Ya",
          type: 'checkbox',
          model: 'asupanMakan',
          value: 1,
        },
        {
          text :"Tidak",
          type: 'checkbox',
          model: 'asupanMakan',
          value: 0,
        },
      ],
    },
  ]
}

export function skriningGizi2(): any {
  return [
    {
      label: '',
      chilren: [],
    },
    {
      label: '',
      chilren: [],
    },
    {
      label: '',
      chilren: [],
    },
  ]
}
