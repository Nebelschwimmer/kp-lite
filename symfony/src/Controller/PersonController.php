<?php

namespace App\Controller;

use App\Dto\Common\LocaleDto;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpKernel\Attribute\MapQueryString;
use App\Model\Response\Entity\Person\PersonPaginateList;
use Psr\Log\LoggerInterface;
use App\Model\Response\Entity\Person\PersonDetail;
use App\Model\Response\Entity\Person\PersonForm;
use Nelmio\ApiDocBundle\Attribute\Model;
use App\Exception\NotFound\PersonNotFoundException;
use Symfony\Component\HttpFoundation\Response;
use App\Service\Entity\PersonService;
use App\Dto\Entity\Person\PersonQueryDto;
use App\Dto\Common\FileNameSearchDto;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use App\Dto\Entity\Person\PersonDto;
use Symfony\Component\HttpFoundation\Request;
use OpenApi\Attributes as OA;
use OpenApi\Attributes\MediaType;
use OpenApi\Attributes\RequestBody;
use OpenApi\Attributes\Schema;
use Symfony\Component\Security\Http\Attribute\CurrentUser;
use App\Entity\User;
#[OA\Tag(name: 'Person')]
class PersonController extends AbstractController
{
	public function __construct(
		private PersonService $personService,
		private LoggerInterface $logger,
	) {
	}
	/**
	 * Find a person by id
	 */
	#[Route(path: '/api/persons/{id}',
		name: 'api_person',
		methods: ['GET'],
		requirements: ['id' => '\d+']
	)
	]
	#[OA\Response(
		response: 200,
		description: 'Successful response',
		content: new Model(type: PersonDetail::class)
	)]
	public function find(int $id, LocaleDto $dto): Response
	{
		$status = Response::HTTP_OK;
		$data = null;

		try {
			$data = $this->personService->get($id, $dto->locale);
		} catch (PersonNotFoundException $e) {
			$status = Response::HTTP_NOT_FOUND;
			$this->logger->error($e);
		}

		return $this->json($data, $status);
	}


	/**
	 * Find a person's form by id
	 */

	#[Route(path: '/api/persons/{id}/form',
		name: 'api_person_form',
		methods: ['GET'],
	)
	]
	#[OA\Response(
		response: 200,
		description: 'Successful response',
		content: new Model(type: PersonForm::class)
	)]
	public function form(int $id): Response
	{
		$status = Response::HTTP_OK;
		$data = null;

		try {
			$data = $this->personService->findForm($id);

		} catch (PersonNotFoundException $e) {
			$status = Response::HTTP_NOT_FOUND;
			$this->logger->error($e);
		}
		return $this->json($data, $status);
	}


	/**
	 * Filter persons by query params
	 */
	#[Route(
		path: 'api/persons/filter',
		name: 'api_person_filter',
		methods: ['GET']
	)]
	#[OA\Response(
		response: 200,
		description: 'Successful response',
		content: new Model(type: PersonPaginateList::class)
	)]

	public function filter(#[MapQueryString] ?PersonQueryDto $dto = new PersonQueryDto()): Response
	{
		return $this->json($this->personService->filter($dto));
	}

	/**
	 * Check if the person list exists
	 */
	#[Route(
		path: 'api/persons/check',
		name: 'api_person_check',
		methods: ['GET']
	)]
	#[OA\Response(
		response: 200,
		description: 'Successful response',
		content: new Model(type: PersonPaginateList::class)
	)]

	public function checkEmpty(): Response
	{
		return $this->json(
			['present' => $this->personService->checkPersonsPresence()]
		);
	}

	/**
	 * Create a new person
	 */
	#[Route(
		path: 'api/persons',
		name: 'api_person_create',
		methods: ['POST']
	)]
	#[OA\Response(
		response: 200,
		description: 'A new person has been created',
		content: new Model(type: PersonForm::class)
	)]
	#[OA\Response(response: 500, description: 'An error occurred while creating the person')]

	public function create(
		#[MapRequestPayload] ?PersonDto $dto,
		#[CurrentUser] User $user,
	): Response {
		$data = null;
		$status = Response::HTTP_OK;

		try {
			$user = $this->getUser();

			$data = $this->personService->create($dto, $user);
		} catch (\Throwable $e) {
			$this->logger->error($e);
			$data = $e->getMessage();
			$status = Response::HTTP_INTERNAL_SERVER_ERROR;
		}

		return $this->json($data, $status);
	}
	/**
	 * Update a person by id
	 */
	#[Route(
		path: 'api/persons/{id}',
		name: 'api_person_update',
		methods: ['POST', 'PUT']
	)]
	#[OA\Response(
		response: 200,
		description: 'A person has been updated',
		content: new Model(type: PersonForm::class)
	)]
	#[OA\Response(response: 500, description: 'An error occurred while updating the person')]

	public function update(
		int $id,
		#[MapRequestPayload] ?PersonDto $dto,
	): Response {

		$data = null;
		$status = Response::HTTP_OK;

		try {

			$data = $this->personService->update($id, $dto);
		} catch (\Throwable $e) {
			$this->logger->error($e);
			$data = $e->getMessage();
			$status = Response::HTTP_INTERNAL_SERVER_ERROR;
		}

		return $this->json($data, $status);
	}

	/**
	 * Delete a person by id
	 */
	#[Route(
		path: 'api/persons/{id}',
		name: 'api_person_delete',
		methods: ['DELETE']
	)]
	#[OA\Response(
		response: 200,
		description: 'The person has been deleted',
	)]
	#[OA\Response(response: 500, description: 'An error occurred while deleting the person')]

	public function delete(
		int $id,
	): Response {

		$data = null;
		$status = Response::HTTP_OK;

		try {
			$this->personService->delete($id);
		} catch (\Throwable $e) {
			$this->logger->error($e);
			$data = $e->getMessage();
			$status = Response::HTTP_INTERNAL_SERVER_ERROR;
		}

		return $this->json($data, $status);
	}

	/**
	 * Upload  photos of the person by their id
	 */

	#[Route(
		path: 'api/persons/{id}/photos',
		name: 'api_person_upload',
		methods: ['POST'],
		requirements: ['id' => '\d+']
	)]
	#[RequestBody(
		content: [
			new MediaType(
				mediaType: 'multipart/form-data',
				schema: new Schema(properties: [
					new OA\Property(
						property: 'photos',
						type: 'file',
					),
				])
			),
		]
	)]
	#[OA\Response(
		response: 200,
		description: 'Upload photos',
	)]
	#[OA\Response(response: 500, description: 'An error occurred while uploading the photos')]

	public function uploadPhotos(
		int $id,
		Request $request,
	): Response {
		$data = null;
		$status = Response::HTTP_OK;

		try {
			$photos = $request->files->get('photos');

			if (null === $photos) {
				$status = Response::HTTP_BAD_REQUEST;
				$data = 'No file found in request. Did you forget to specify the formdata key "photos"?';
				return $this->json($data, $status);
			}
			$data = $this->personService->uploadPhotos($id, $photos);
		} catch (\Throwable $e) {
			$this->logger->error($e);
			$data = $e->getMessage();
			$status = Response::HTTP_INTERNAL_SERVER_ERROR;
		}

		return $this->json($data, $status);
	}

	/**
	 * Delete the photos of a person by their id
	 */
	#[Route(
		path: 'api/persons/{id}/photos',
		name: 'api_person_delete_photos',
		methods: ['DELETE']
	)]
	#[OA\Response(
		response: 200,
		description: 'Delete photos if exists',
	)]
	#[OA\Response(response: 500, description: 'An error occurred while uploading the photos')]
	public function deletePhoto(
		int $id,
		#[MapRequestPayload] ?FileNameSearchDto $dto,
	): Response {
		$data = null;
		$status = Response::HTTP_OK;

		try {
			if (empty($dto->fileNames)) {
				$status = Response::HTTP_BAD_REQUEST;
				$data = 'The request is empty. No file names found.';
				return $this->json($data, $status);
			}
			$data = $this->personService->deletePhotos($id, $dto->fileNames);
		} catch (\Throwable $e) {
			$this->logger->error($e);
			$data = $e->getMessage();
			$status = Response::HTTP_INTERNAL_SERVER_ERROR;
		}

		return $this->json($data, $status);
	}

	/**
	 * Upload cover
	 */
	#[Route(
		path: 'api/persons/{id}/cover',
		name: 'api_person_cover',
		methods: ['POST']
	)]
	#[OA\Response(
		response: 200,
		description: 'Upload cover',
	)]
	#[OA\Response(response: 500, description: 'An error occurred while uploading the photos')]
	public function uploadCover(
		int $id,
		Request $request,
	): Response {

		$data = null;
		$status = Response::HTTP_OK;

		try {
			$cover = $request->files->get('file');
			if (null === $cover) {
				$status = Response::HTTP_BAD_REQUEST;
				$data = 'No file found in request. Did you forget to specify the formdata key "file"?';
				return $this->json($data, $status);
			}
			$data = $this->personService->uploadCover($id, $cover);
		} catch (\Throwable $e) {
			$this->logger->error($e);
			$data = $e->getMessage();
			$status = Response::HTTP_INTERNAL_SERVER_ERROR;
		}

		return $this->json($data, $status);

	}

	/**
	 * List all specialists
	 */
	#[Route(
		path: 'api/persons/specialists',
		name: 'api_person_specialists',
		methods: ['GET']
	)]
	#[OA\Response(
		response: 200,
		description: 'List all specialists',
	)]
	#[OA\Response(response: 500, description: 'An error occurred ')]

	public function specialists(): Response
	{

		$data = null;
		$status = Response::HTTP_OK;

		try {
			$data = $this->personService->listSpecialists();
		} catch (\Throwable $e) {
			$this->logger->error($e);
			$data = $e->getMessage();
			$status = Response::HTTP_INTERNAL_SERVER_ERROR;
		}

		return $this->json($data, $status);
	}

	/**
	 * Get popular actors
	 */
	#[Route(
		path: 'api/persons/actors-popular',
		name: 'api_person_actors_popular',
		methods: ['GET']
	)]
	#[OA\Response(
		response: 200,
		description: 'Get popular actors',
	)]
	#[OA\Response(response: 500, description: 'An error occurred ')]

	public function popluar(): Response
	{

		$data = null;
		$status = Response::HTTP_OK;

		try {
			$data = $this->personService->listPopularActors();
		} catch (\Throwable $e) {
			$this->logger->error($e);
			$data = $e->getMessage();
			$status = Response::HTTP_INTERNAL_SERVER_ERROR;
		}

		return $this->json($data, $status);
	}
}
