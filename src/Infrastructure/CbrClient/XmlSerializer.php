<?php

namespace App\Infrastructure\CbrClient;

use Symfony\Component\PropertyInfo\Extractor\PhpDocExtractor;
use Symfony\Component\PropertyInfo\Extractor\ReflectionExtractor;
use Symfony\Component\PropertyInfo\Extractor\SerializerExtractor;
use Symfony\Component\PropertyInfo\PropertyInfoExtractor;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Mapping\Factory\ClassMetadataFactory;
use Symfony\Component\Serializer\Mapping\Loader\AttributeLoader;
use Symfony\Component\Serializer\NameConverter\CamelCaseToSnakeCaseNameConverter;
use Symfony\Component\Serializer\NameConverter\MetadataAwareNameConverter;
use Symfony\Component\Serializer\Normalizer\ArrayDenormalizer;
use Symfony\Component\Serializer\Normalizer\GetSetMethodNormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Normalizer\PropertyNormalizer;
use Symfony\Component\Serializer\Serializer;

class XmlSerializer extends Serializer
{
    public function __construct()
    {
        $classMetadataFactory = new ClassMetaDataFactory(
            new AttributeLoader
        );
        $metadataAwareNameConverter = new MetadataAwareNameConverter($classMetadataFactory, new CamelCaseToSnakeCaseNameConverter());
        $encoders = [new XmlEncoder()];
        $extractors = new PropertyInfoExtractor(
            listExtractors: [new SerializerExtractor($classMetadataFactory), new ReflectionExtractor()],
            typeExtractors: [new PhpDocExtractor(), new ReflectionExtractor()]
        );
        $normalizers = [
            new ArrayDenormalizer(),
            new GetSetMethodNormalizer(
                $classMetadataFactory,
                $metadataAwareNameConverter,
                propertyTypeExtractor: $extractors)
            ,
            new PropertyNormalizer(
                $classMetadataFactory,
                $metadataAwareNameConverter,
                propertyTypeExtractor: $extractors
            ),
        ];
        parent::__construct($normalizers, $encoders);
    }
}