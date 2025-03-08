export default () => {
  const letters = "0123456789ABCDEF";
  let color = "#";
  const opacity = "20";
  for (let i = 0; i < 6; i++) {
    color += letters[Math.floor(Math.random() * 16)];
  }

  color += opacity;
  return color;
};
