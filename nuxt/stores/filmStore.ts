import { useAuthStore } from "./authStore";

export const useFilmStore = defineStore("films", () => {
  interface IFilmListResponse {
    items: IFilm[];
    totalPages: number;
    currentPage: number;
  }
  const authStore = useAuthStore();
  const film = ref<IFilm | null>(null);
  const films = ref<IFilm[]>([]);
  const currentPage = ref<number>(1);
  const totalPages = ref<number>(0);
  const genres = ref<IGenre[]>([]);
  const loading = ref<boolean>(false);
  const total = ref<number>(0);
  const latestFilms = ref<IFilm[]>([]);
  const directors = ref<Partial<IPerson>[]>([]);
  const actors = ref<Partial<IPerson>[]>([]);
  const producers = ref<Partial<IPerson>[]>([]);
  const writers = ref<Partial<IPerson>[]>([]);
  const composers = ref<Partial<IPerson>[]>([]);
  const filmsPresent = ref<boolean>(false);
  const similarGenreFilms = ref<IFilm[]>([]);
  const authHeaders = computed(() => {
    return {
      Authorization: `Bearer ${authStore.token}`,
    };
  });
  const baseUrl = useRuntimeConfig().public.apiBase;
  const defaultFilmValues: Partial<IFilm> = {
    id: null,
    name: "",
    slogan: "",
    genreIds: [],
    releaseYear: 0,
    actorIds: [],
    directorId: null,
    producerId: null,
    composerId: null,
    writerId: null,
    description: "",
    age: 0,
    duration: "",
    gallery: [],
    cover: "",
  };
  const filmForm = ref<Partial<IFilm>>({ ...defaultFilmValues });
  const GALLERY_SIZE: number = 8;
  const fetchFilteredFilms = async (
    limit: number,
    offset: number,
    search: string,
    order: string,
    sortBy: string,
    locale: string
  ): Promise<void> => {
    try {
      loading.value = true;
      const response = await $fetch<IFilmListResponse>(
        `${baseUrl}/films/filter?limit=${limit}&offset=${offset}&search=${search}&order=${order}&sortBy=${sortBy}&locale=${locale}`
      );
      films.value = response?.items || [];
      currentPage.value = response?.currentPage || 1;
      totalPages.value = response?.totalPages || 0;
    } catch (error: unknown) {
      handleError(error);
    } finally {
      loading.value = false;
    }
  };

  const fetchLatestFilms = async (): Promise<void> => {
    try {
      loading.value = true;
      const response = await $fetch<IFilmListResponse>(
        `${baseUrl}/films/latest`
      );
      latestFilms.value = response?.items || [];
    } catch (error: unknown) {
      handleError(error);
    } finally {
      loading.value = false;
    }
  };

  const fetchFilmsWithSimilarGenres = async (id: number): Promise<void> => {
    try {
      loading.value = true;
      const response = await $fetch<IFilmListResponse>(
        `${baseUrl}/films/similar-genres/${id}`
      );
      similarGenreFilms.value = response?.items || [];
    } catch (error: unknown) {
      handleError(error);
    } finally {
      loading.value = false;
    }
  };

  const fetchFilmById = async (id: number, locale: string): Promise<void> => {
    try {
      loading.value = true;
      const response = await $fetch<IFilm>(
        `${baseUrl}/films/${id}?locale=${locale}`
      );
      film.value = response;
    } catch (error: unknown) {
      handleError(error);
    } finally {
      loading.value = false;
    }
  };
  const fetchFilmForm = async (id: number, locale: string): Promise<void> => {
    try {
      loading.value = true;
      const response = await $fetch<Partial<IFilm>>(
        `${baseUrl}/films/${id}/form?locale=${locale}`
      );
      filmForm.value = response || {};
    } catch (error: unknown) {
      handleError(error);
    } finally {
      loading.value = false;
    }
  };

  const fetchGenres = async (locale: string): Promise<void> => {
    try {
      loading.value = true;
      const response = await $fetch<Array<IGenre>>(
        `${baseUrl}/genres/translations?locale=${locale}`
      );
      genres.value = response || [];
    } catch (error: unknown) {
      handleError(error);
    } finally {
      loading.value = false;
    }
  };

  const fetchSpecialists = async (): Promise<void> => {
    try {
      loading.value = true;
      const response = await $fetch<ISpecialistsListResponse>(
        `${baseUrl}/persons/specialists`
      );
      composers.value = response?.composers.items || [];
      directors.value = response?.directors.items || [];
      actors.value = response?.actors.items || [];
      producers.value = response?.producers.items || [];
      writers.value = response?.writers.items || [];
    } catch (error: unknown) {
      handleError(error);
    } finally {
      loading.value = false;
    }
  };

  const addFilm = async (): Promise<boolean> => {
    try {
      loading.value = true;
      const response = await $fetch<IFilm>(`${baseUrl}/films`, {
        headers: authHeaders.value,
        method: "POST",
        body: filmForm.value,
      });
      filmForm.value = response || {};
      return true;
    } catch (error: unknown) {
      handleError(error);
      return false;
    } finally {
      loading.value = false;
    }
  };

  const editFilm = async (locale: string): Promise<boolean> => {
    try {
      loading.value = true;
      const response = await $fetch<IFilm>(
        `${baseUrl}/films/${filmForm.value.id}?locale=${locale}`,
        {
          method: "PUT",
          body: filmForm.value,
        }
      );
      film.value = response;
      return true;
    } catch (error: unknown) {
      handleError(error);
      return false;
    } finally {
      loading.value = false;
    }
  };

  const uploadGallery = async (gallery: File[], id: number): Promise<void> => {
    try {
      loading.value = true;

      const formData = new FormData();
      gallery.forEach((file) => {
        formData.append("gallery[]", file);
      });
      const response = await $fetch<IFilm>(`${baseUrl}/films/${id}/gallery`, {
        method: "POST",
        body: formData,
      });
      film.value = response;
      filmForm.value = response;
    } catch (error: unknown) {
      handleError(error);
    } finally {
      loading.value = false;
    }
  };

  const deleteGalleryItems = async (fileNames: string[]): Promise<boolean> => {
    try {
      loading.value = true;
      const id = film.value?.id || 0;
      const response = await $fetch<IFilm>(`${baseUrl}/films/${id}/gallery`, {
        method: "DELETE",
        body: JSON.stringify({ fileNames: fileNames }),
      });
      filmForm.value = response || {};
      return true;
    } catch (error: unknown) {
      handleError(error);
      return false;
    } finally {
      loading.value = false;
    }
  };

  const clearFilmForm = (): void => {
    filmForm.value = {} as IFilm;
  };

  const assessFilmById = async (
    id: number,
    rating: number,
    comment: string,
    locale: string
  ): Promise<void> => {
    try {
      await $fetch(`${baseUrl}/films/${id}/assess?locale=${locale}`, {
        headers: authHeaders.value,
        method: "POST",
        body: JSON.stringify({ rating, comment }),
      });
    } catch (error: unknown) {
      handleError(error);
    }
  };

  const checkFilmsPresence = async () => {
    try {
      loading.value = true;
      const response = await $fetch<ICheckEmptyResponse>(
        `${baseUrl}/films/check`
      );
      filmsPresent.value = response?.present || false;
    } catch (error: unknown) {
      handleError(error);
      return false;
    } finally {
      loading.value = false;
    }
  };

  const deleteFilm = async (id: number) => {
    try {
      loading.value = true;
      await $fetch(`${baseUrl}/films/${id}`, {
        method: "DELETE",
      });
    } catch (error: unknown) {
      handleError(error);
    } finally {
      loading.value = false;
    }
  };

  return {
    film,
    films,
    filmForm,
    genres,
    directors,
    loading,
    actors,
    producers,
    writers,
    total,
    composers,
    currentPage,
    totalPages,
    latestFilms,
    GALLERY_SIZE,
    filmsPresent,
    similarGenreFilms,
    fetchFilteredFilms,
    fetchGenres,
    addFilm,
    fetchFilmById,
    uploadGallery,
    fetchLatestFilms,
    fetchFilmForm,
    editFilm,
    clearFilmForm,
    deleteGalleryItems,
    assessFilmById,
    checkFilmsPresence,
    deleteFilm,
    fetchSpecialists,
    fetchFilmsWithSimilarGenres,
  };
});
