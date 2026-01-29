export const nickMocks = {
  response: (() => {
    const values = [
       {id: 123, gender: 'NEUTRAL', offenseLevel: '5', words: [{id: 1, label: 'Dark'}, {id: 2, label: 'Fox'}]},
       {id: 456, gender: 'NEUTRAL', offenseLevel: '10', words: [{id: 3, label: 'Little'}, {id: 4, label: 'Mouse'}]}
    ];
    let i = 0;

    return {
      status: 200,
      contentType: 'application/json',
      body: JSON.stringify(values[i++] ?? values.at(-1)),
    };
  }),
};
