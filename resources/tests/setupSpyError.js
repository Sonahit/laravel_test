const { error: originalError } = console;

beforeAll(() => {
  jest.spyOn(console, 'error').mockImplementation((...args) => {
    originalError(...args);
    const error = util.format.apply(this, rest);
    throw new Error(error);
  });
});

afterAll(() => {
  console.error.mockRestore();
});

afterEach(() => {
  console.error.mockClear();
});
