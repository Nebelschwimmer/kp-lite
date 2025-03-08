export default (title: string) => {
  const runtimeConfig = useRuntimeConfig();
  return runtimeConfig.public.appName + (title ? " | " + title : '');
};
