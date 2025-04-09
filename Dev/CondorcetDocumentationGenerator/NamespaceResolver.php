<?php
declare(strict_types=1);

namespace CondorcetPHP\Condorcet\Dev\CondorcetDocumentationGenerator;

use PhpParser\Error;
use PhpParser\Node;
use PhpParser\NodeTraverser;
use PhpParser\NodeVisitorAbstract;
use PhpParser\ParserFactory;

class NamespaceResolver extends NodeVisitorAbstract {

    public static function resolveClassName(string $className, string $filePath): string {
        $code = file_get_contents($filePath);

        $parser = new ParserFactory()->createForHostVersion();
        $traverser = new NodeTraverser();
        $namespaceResolver = new self();
        $traverser->addVisitor($namespaceResolver);

        try {
            $stmts = $parser->parse($code);
            $traverser->traverse($stmts);
        } catch (Error $error) {
            echo "Erreur de parsing : ", $error->getMessage();
            exit(1);
        }

        // Résoudre le nom de la classe
        if (isset($namespaceResolver->uses[$className])) {
            return $namespaceResolver->uses[$className];
        }

        if (class_exists($namespaceResolver->namespace . '\\' . $className)) {
            return $namespaceResolver->namespace . '\\' . $className;
        }

        return $className;
    }

    public string $namespace = '';
    public array $uses = [];

    public function enterNode(Node $node): void {
        if ($node instanceof Node\Stmt\Namespace_) {
            $this->namespace = $node->name ? $node->name->toString() : '';
        }

        if ($node instanceof Node\Stmt\Use_) {
            foreach ($node->uses as $use) {
                // Gérer le cas où alias est null
                if ($use->alias !== null) {
                    $this->uses[$use->alias->name] = $use->name->toString();
                } else {
                    // Utiliser le dernier segment du nom comme clé
                    $parts = $use->name->getParts();
                    $this->uses[end($parts)] = $use->name->toString();
                }
            }
        }

        // Gérer les déclarations groupées de use
        if ($node instanceof Node\Stmt\GroupUse) {
            $prefix = $node->prefix->toString();
            foreach ($node->uses as $use) {
                $name = $prefix . '\\' . $use->name->toString();

                if ($use->alias !== null) {
                    $this->uses[$use->alias->name] = $name;
                } else {
                    $parts = $use->name->getParts();
                    $this->uses[end($parts)] = $name;
                }
            }
        }
    }
}
