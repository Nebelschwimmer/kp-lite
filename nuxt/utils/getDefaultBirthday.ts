export default () => {
  const now = new Date();
  now.setFullYear(now.getFullYear() - 18);
  return now.toISOString().slice(0, 10);
}
