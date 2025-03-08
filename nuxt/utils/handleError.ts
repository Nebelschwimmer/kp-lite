export default (error: unknown) => {
  const typedError = error as { statusCode: number };
  throw createError({
    statusCode: typedError.statusCode,
    fatal: true,
  });
};
